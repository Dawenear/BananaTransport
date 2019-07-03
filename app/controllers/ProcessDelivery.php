<?php

namespace App\Controllers;
use App\Models\DeliveryStep;
use JsonSchema\Constraints\Constraint;
use JsonSchema\Exception\ValidationException;
use JsonSchema\Validator;

class ProcessDelivery
{
    /** @var DeliveryStep[] */
    private $notes = [];

    /**
     * @param $notes string
     */
    public function handleRoute($notes)
    {
        try {
            $this->validateNotes($notes);
        } catch (ValidationException $e) {
            echo 'Json file doesn\'t match schema';
            die();
        }

        try {
            $this->createNotes($notes);
        } catch (\Exception $e) {
            echo 'got empty json, aborting';
            die();
        }
        try {
            $this->reorderArray();
        } catch (\Exception $e) {
            echo 'Chain is broken - script was unable to solve this pathway';
            die();
        }
        $this->printOutput();


    }

    /**
     * @param $notes string
     * @throws ValidationException
     */
    private function validateNotes($notes)
    {
        $data = json_decode($notes);

        $validator = new Validator;
        try {
            $validator->validate($data, (object)['$ref' => 'file://' . realpath('schema.json')], Constraint::CHECK_MODE_EXCEPTIONS);
        } catch (ValidationException $e) {
            throw new ValidationException('Json file doesn\'t match schema');
        }

        if ($validator->isValid()) {
//            echo "The supplied JSON validates against the schema.\n";
        } else {
            echo "JSON does not validate. Violations:\n";
            foreach ($validator->getErrors() as $error) {
                echo sprintf("[%s] %s\n", $error['property'], $error['message']);
            }
            throw new ValidationException('Invalid json schema');
        }
        return;
    }

    /**
     * @param $notes string
     * @throws \Exception
     */
    private function createNotes($notes)
    {
        $array = json_decode($notes, true);
        if (!$array) {
            throw new \Exception('empty json');
        }
        foreach ($array as $note) {
            $this->notes[] = new DeliveryStep($note);
        }
    }

    /**
     * @throws \Exception
     */
    private function reorderArray()
    {
        $helpArray = $this->notes;
        $this->notes = [];
        $this->notes[0] = $helpArray[0];
        $startLocation = $helpArray[0]->getStartLocation();
        $endLocation = $helpArray[0]->getEndLocation();
        $countNotes = count($helpArray);
        unset($helpArray[0]);


        while (count($this->notes) !== $countNotes) {
            $currentCount = count($this->notes);
            foreach ($helpArray as $index => $helpNote) {
                if ($startLocation === $helpNote->getEndLocation()) {
                    $startLocation = $helpNote->getStartLocation();
                    array_unshift($this->notes , $helpNote);
                    unset($helpArray[$index]);
                } elseif ($endLocation === $helpNote->getStartLocation()) {
                    $endLocation = $helpNote->getEndLocation();
                    $this->notes[] = $helpNote;
                    unset($helpArray[$index]);
                }
            }
            if ($currentCount === count($this->notes)) {
                throw new \Exception('This chain is broken');
            }
        }
    }

    private function printOutput()
    {
        $output = [];
        foreach ($this->notes as $note) {
            $output[] = 'From ' . $note->getStartLocation() . ' to ' . $note->getEndLocation() . ' by ' . $note->getTransportMethod() . ' (' . $note->getDeliveryCompany() . ')';
        }
        $output = json_encode($output, JSON_UNESCAPED_UNICODE);
        echo $output;
    }
}