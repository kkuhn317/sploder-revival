<?php

class EventEmitterService
{
    private static EventEmitter|null $value = null;

    public static function get(): EventEmitter
    {
        if (EventEmitter::$value == null) {
            EventEmitter::$value = new EventEmitter("../cache/events.xml");
        }
        return EventEmitter::$value;
    }

    private readonly SyncMutex $mutex;
    private readonly string $fileName;
    private SimpleXMLElement $xml;

    private function __construct(string $fileName)
    {
        $this->mutex = new SyncMutex("EventEmitterMutex");
        $this->fileName = $fileName;
        $this->xml = null;
    }

    public function emitEvent(): void
    {
        // Add some arguments eventually
        $xml = $this->getXml();
        if (!$this->mutex->lock()) {
            throw new Exception("Failed to acquire lock");
        }

        try {
            // Update the XML with the new event
            $xml->asXML($this->fileName);
        } finally {
            $this->mutext->unlock();
        }
    }

    private function getXml(): SimpleXMLElement
    {
        if ($this->xml !== null) {
            return $this->xml;
        }

        if (!$this->mutex->lock()) {
            throw new Exception("Failed to acquire lock");
        }

        if ($this->xml !== null) {
            return $this->xml;
        }

        try {
            try {
                $xml = simplexml_load_file($this->fileName);

                if (!$xml) {
                    throw new Exception("Failed to load xml file");
                }

                $this->xml = $xml;
                return $xml;
            } catch (Exception $ex) {
                // Initialize a new XML file
                $xml = new SimpleXMLElement("<events></events>");
                $xml->asXML($this->fileName);
                return $xml;
            }
        } finally {
            $this->mutex->unlock();
        }
    }
}
