<?php

namespace Models;

class Reservation
{
    private $destination;
    private $insurance;
    private $personsCounter;
    private $persons;
    private $price;
    private $warning; // <- not persistent in db

    function __construct()
    {
        $this->destination    = '';
        $this->personsCounter = 1;
        $this->insurance      = 'False';
        $this->persons        = array();
        $this->price          = 0;
        $this->warning        = '';
    }

    /**
     * Generic getter
     * @param the name of the property to return
     * @return the value of the property
     */
    public function __get($property)
    {
        if (property_exists($this, $property))
        {
            return $this->$property;
        }
    }

    /**
     * Generic setter
     * @param the name of the property to set
     * @return none
     */
    public function __set($property, $value)
    {
        if (property_exists($this, $property))
        {
            $this->$property = $value;
        }
    }

    /**
     * Restore all the properties by default.
     * This method is useful to avoid the 'Trying to get property of non-object' error.
     * @param none
     * @return none
     */
    public function reset()
    {
        $this->destination    = '';
        $this->personsCounter = 1;
        $this->insurance      = 'False';
        $this->persons        = array();
        $this->price          = 0;
        $this->warning        = '';
        $this->save();
    }

    /**
     * Save the current instance of Reservation to the session.
     * @param none
     * @return none
     */
    public function save()
    {
        $_SESSION['reservation'] = serialize($this);
    }

    /**
     * Append every warning message to a single string that will be used
     * to warn the user about inadequate informations.
     * @param the message to append to warnings
     * @return none
     */
    public function append_warning($message)
    {
        $this->warning .= $message;
    }

    /**
     * Clean the warning string and save it.
     * @param none
     * @return none
     */
    public function reset_warning()
    {
        $this->warning = '';
        $this->save();
    }

    /**
     * Calculate the amount to pay with the number of persons and their age.
     * @param none
     * @return none
     */
    public function calculate_amount()
    {
        define('INSURANCE', 20);
        define('CHILD_PRICE', 10);
        define('ADULT_PRICE', 15);

        $amount = 0;

        if ($this->insurance == 'True')
            $amount += INSURANCE;

        foreach ($this->persons as $person)
        {
            if ($person->age <= 12)
                $amount += CHILD_PRICE;
            else
                $amount += ADULT_PRICE;
        }

        $this->price = $amount;
    }
}

class Person
{
    private $fullname;
    private $age;

    function __construct($fullname, $age)
    {
        $this->fullname = htmlspecialchars($fullname);
        $this->age      = intval($age);
    }

    /**
     * Generic getter
     * @param the name of the property to return
     * @return the value of the property
     */
    public function __get($property)
    {
        if (property_exists($this, $property))
        {
            return $this->$property;
        }
    }

    /**
     * Generic setter
     * @param the name of the property to set
     * @return none
     */
    public function __set($property, $value)
    {
        if (property_exists($this, $property))
        {
            $this->$property = $value;
        }
    }

    /**
     * Combine the attributes in a single string.
     * @param none
     * @return the stringified attributes of the object
     */
    public function toString()
    {
        return $this->fullname." - ".$this->age." ans\n";
    }

}

?>
