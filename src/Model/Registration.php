<?php

namespace App\Model;

use App\Document\User;
use Symfony\Component\Validator\Constraints as Assert;

class Registration
{
    /**
     * @Assert\Type(type="App\Document\User")
     */
    protected User $user;

    /**
     * @Assert\NotBlank()
     */
    protected bool $termsAccepted;

    public function setUser(User $user): void
    {
        $this->user = $user;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function getTermsAccepted(): bool
    {
        return $this->termsAccepted;
    }

    public function setTermsAccepted($termsAccepted): void
    {
        $this->termsAccepted = (boolean)$termsAccepted;
    }
}