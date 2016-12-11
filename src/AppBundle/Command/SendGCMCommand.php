<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class SendGCMCommand extends ContainerAwareCommand {

    private $telefonos = null;

    protected function configure() {
        $this
                ->setName('ydi:gcm:send')
                ->setDescription('Enviar notificaciones push a la aplicacion YDI')
                ->addArgument('token', InputArgument::OPTIONAL, 'A que token lo enviamos?')
                ->addOption(
                        'envios', null, InputOption::VALUE_OPTIONAL, 'Cuantas notificaciones vas a enviar?', 10
                )
                ->addOption(
                        'codigo', null, InputOption::VALUE_OPTIONAL, 'Que codigo se va a enviar?', 1
                )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output) {
        $ids = array();
        $token = $input->getArgument('token');
        $envios = 0;
        $telefonos = [];

        $em = $this->getContainer()->get('doctrine')->getManager();

        if (strlen($token)) {
            $codigo = $input->getOption('codigo');
            $output->writeln('Enviando codigo: ' . $codigo);
            $telefono = new \YDI\BackendBundle\Entity\Telefono();
            //$telefono->setUsuario(null);
            $telefono->setCodigosNotificacion($codigo);
            $telefono->setEnviarNotificacion(true);
            $telefono->setGcmid($token);
            $telefono->setFechaDescarga(new \DateTime());
            $this->telefonos = array($telefono);
            $output->writeln('Telefonos agregado' . count($telefonos));
        } else {
            $envios = $input->getOption('envios');
            $this->telefonos = $em->getRepository('YDIBackendBundle:Telefono')
                    ->getTelefonosEnviarNotificacion($envios);
            $output->writeln('Telefonos agregados: ' . count($this->telefonos));
        }
        $this->crearNotificaciones($this->telefonos, $em, $output);
    }

    protected function crearNotificaciones($telefonos, $em, OutputInterface $output) {
        $codigos = [];
        $notificaciones = [];
        $logger = $this->getContainer()->get('logger');
        foreach ($telefonos as $telefono) {
            $tokenId = $telefono->getGcmid();
            if ($telefono->hasCodigosNotificacion()) {
                $codigos = $telefono->getCodigosNotificacion();
                $output->write("Codigos extraidos: " . count($codigos));
                $tokenId = $telefono->getGcmid();
                foreach ($codigos as $codigo) {
                    if (!isset($notificaciones[$codigo])) {
                        $notificaciones[$codigo] = array();
                        $output->writeln("Codigo agregado a notificaciones: $codigo");
                    }
                    $notificaciones[$codigo][] = $tokenId;
                    $output->writeln("Agregando tokenId a la notificacion: $tokenId");
                }
            } else {
                $telefono->setEnviarNotificacion(false);
                $em->flush();
                $output->writeln('No tiene codigos de notificaciones el telefono: ' . $telefono->getGcmid());
            }
        }
        $em->flush();
        if (count($notificaciones) > 0) {
            $output->writeln('Enviando notificaciones');
            $logger->info("Agregando los registros de notificaciones: " . count($notificaciones));
            $this->enviarNotificaciones($notificaciones, $em, $output);
        }else{
            $logger->info("No hay notificaciones por enviar");
        }
    }

    protected function enviarNotificaciones($notificaciones, $em, OutputInterface $output) {
        $logger = $this->getContainer()->get('logger');
        $fecha = new \DateTime();
        $logger->info("Agregando los registros de notificaciones: " . count($notificaciones));
        $output->writeln("Agregando los registros de notificaciones: " . count($notificaciones));
        $salir = false;
        $output->writeln(print_r($notificaciones));
        $mensajeError = '';
        foreach ($notificaciones as $key => $notificacion) {
            $data = array('codigo' => $key);
            $responses = $this->gcmSend($data, $notificacion, array('dry_run' => false, 'collapse_key' => $this->getCollapseKey($key)));
            // respuesta de notificaciones.
            $output->writeln("Codigo $key, notificaciones: " . count($notificacion));
            $output->writeln(print_r($responses));
            foreach ($responses as $response) {
                $message = json_decode($response->getContent());
                $output->writeln(print_r($message));
                $output->writeln(print_r($message->results));
                if ($message === null) {

                } else if ($message->success > 0 || $message->failure > 0) {
                    $output->writeln(print_r($message->results));
                    foreach ($message->results as $indice => $resultado) {
                        if (!isset($resultado->error) && isset($resultado->message_id)) {
                            $resultado->error = 'Ninguno';
                            $output->writeln("Paso por aqui con " . $resultado->message_id);
                        }
                        switch ($resultado->error) {
                            case 'Unavailable':
                                $mensajeError = "Servicio no disponible";
                                $salir = true;
                                break;
                            case 'NotRegistered':
                                $telefono = $this->telefonos[$indice];
                                $gcmid = $telefono->getGcmid();
                                $mensajeError = "El GCMID: $gcmid NotRegistered, GCMID no registrado";
                                $output->writeln($mensajeError);
                                $logger->info($mensajeError);
                                if ($telefono) {
									$telefono->setEnviarNotificacion(false);
                                    $telefono->setNotificacionAnuncio(false);
                                    $telefono->setNotificacionNoticias(false);
                                    $telefono->setNotificacionTarEmOper(false);
                                    $telefono->setInactivo(true);
                                    $em->flush();
                                }
                                break;
                            case 'InvalidRegistration':
                                $telefono = $this->telefonos[$indice];
                                $gcmid = $telefono->getGcmid();
                                $mensajeError = "El GCMID: $gcmid InvalidRegistration, GCMID es registro invalido";
                                $output->writeln($mensajeError);
                                $logger->info($mensajeError);
                                if ($telefono) {
                                    $telefono->setEnviarNotificacion(false);
                                    $telefono->setNotificacionAnuncio(false);
                                    $telefono->setNotificacionNoticias(false);
                                    $telefono->setNotificacionTarEmOper(false);
                                    $telefono->setInactivo(true);
                                    $em->flush();
                                }
                                break;
                            case 'Ninguno':
                                $telefono = $this->telefonos[$indice];
                                $gcmid = $telefono->getGcmid();
                                $output->writeln("Ninguno: $indice.-$gcmid");
                                $logger->info("Ninguno: $indice.-$gcmid");
                                $output->writeln("Removiendo el codigo de notificacion: $key");
                                $logger->info("Removiendo el codigo de notificacion: $key");
                                $telefono->removeCodigoNotificacion($key);
                                $output->writeln("Revisando si hay mas notificaciones: " . ($telefono->hasCodigosNotificacion()?"Si":"No"));
                                $logger->info("Revisando si hay mas notificaciones: " . ($telefono->hasCodigosNotificacion()?"Si":"No"));
                                $telefono->setEnviarNotificacion(($telefono->hasCodigosNotificacion()?true:false));
                                $em->persist($telefono);
                                $em->flush();
                                break;
                            default:
                                $mensajeError = "Error desconocido: " . $resultado->error;
                        }
                        $logger->info($mensajeError);
                        $output->writeln("<error>$mensajeError</error>");
                        if ($salir) {
                            break;
                        }
                    }
                }
            }
        }
    }

    private function getCollapseKey($key) {
        if ($key == "11") {
            return "$key.- anuncio";
        } else if ($key == "12") {
            return "$key.- tarjeta_emisor_operador";
        } else if ($key == "13") {
            return "$key.- noticias";
        }
    }

    public function gcmSend($data, $ids, $options) {
        $gcm = $this->getContainer()->get('endroid.gcm.client');

        $gcm->send($data, $ids, $options);

        $responses = $gcm->getResponses();

        return $responses;
    }

}
