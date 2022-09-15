<?php

namespace App\View\Components;

use Illuminate\View\Component;

class ProfileForm extends Component
{
    /**
     * The diet type.
     *
     * @var integer
     */
    public $dietType;

    /**
     * The diet target.
     *
     * @var integer
     */
    public $dietTarget;

    /**
     * The user's gender.
     *
     * @var integer
     */
    public $gender;

    /**
     * The user's birthday.
     *
     * @var string
     */
    public $birthday;

    /**
     * The user's weight.
     *
     * @var integer
     */
    public $weight;

    /**
     * The user's height.
     *
     * @var integer
     */
    public $height;

    /**
     * The user's target weight.
     *
     * @var integer
     */
    public $targetWeight;

    /**
     * The user's basic activity.
     *
     * @var integer
     */
    public $basicActivity;

    /**
     * The user's sport activity.
     *
     * @var integer
     */
    public $sportActivity;

    /**
     * The forms edit status.
     *
     * @var boolean
     */
    public $edit;


    /**
     * Create a new component instance.
     *
     * @param integer dietType
     * @param integer dietTarget
     * @param integer gender
     * @param string birthday
     * @param integer weight
     * @param integer height
     * @param integer targetWeight
     * @param integer basicActivity
     * @param integer sportActivity
     * @param boolean edit
     * @return void
     */

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($dietType = NULL, $dietTarget = NULL, $gender = NULL, $birthday = NULL, $weight = NULL, $height = NULL, $targetWeight = NULL, $basicActivity = NULL, $sportActivity = NULL, $edit = 'false')
    {
        $this->dietType = $dietType;
        $this->dietTarget = $dietTarget;
        $this->gender = $gender;
        $this->birthday = $birthday;
        $this->weight = $weight;
        $this->height = $height;
        $this->targetWeight = $targetWeight;
        $this->basicActivity = $basicActivity;
        $this->sportActivity = $sportActivity;
        $this->edit = $edit;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.profile-form');
    }
}
