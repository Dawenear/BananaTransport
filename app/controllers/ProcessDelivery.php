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

    public function handleRoute($notes)
    {
        try {
            $this->validateNotes($notes);
        } catch (ValidationException $e) {
            echo 'Json file doesn\'t match schema';
            die();
        }

        $this->createNotes($notes);
        $this->reorderArray();
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
     */
    private function createNotes($notes)
    {
        $array = json_decode($notes, true);
        foreach ($array as $note) {
            $this->notes[] = new DeliveryStep($note);
        }
    }

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