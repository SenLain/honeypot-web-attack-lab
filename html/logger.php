<?php
class Logger {
    private $logFile = 'customLog.log';

    public function log($user, $attackType, $payload) {
        $dateTime = new DateTime('now',new DateTimeZone('Europe/Brussels'));
        $timestamp = $dateTime->format('Y-m-d H:i:s');

        $logMessage = "Time: " . $timestamp . " | Username: " . $user . " | AttackType: " . $attackType . " | Payload: " . $payload . "\n";

        file_put_contents($this->logFile, $logMessage, FILE_APPEND);
    }
}
?>