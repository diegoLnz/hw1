<?php

enum TraceLevel: string
{
    case Info = "message";
    case Error = "error";
}

class Diagnostics
{
    private static XmlBuilder $xmlBuilder;
    private static string $logFileDirectory = "Logs";
    private static function getLogFileName(): string{
        return "log". date("dmy") .".json";
    }

    public static function traceMessage(string $message, TraceLevel $traceLevel = TraceLevel::Info, string $caller = ""): void
    {
        $logData = [
            $traceLevel->value => [
                'level' => $traceLevel->name,
                'message' => $message,
                'timestamp' => date(DateTimeInterface::ISO8601),
                'caller' => $caller
            ]
        ];

        if (!is_dir(self::$logFileDirectory)){
            mkdir(self::$logFileDirectory);
        }

        $filePath = self::$logFileDirectory . "/" . self::getLogFileName();

        $currentContent = [];
        if (file_exists($filePath)) {
            $currentContent = json_decode(file_get_contents($filePath), true);
        }

        $currentContent[] = $logData;

        $logEntries = json_encode($currentContent, JSON_PRETTY_PRINT);

        file_put_contents($filePath, $logEntries . PHP_EOL);
    }
}
