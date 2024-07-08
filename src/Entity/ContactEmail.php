<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;

class ContactEmail
{
    /**
     * @var string|null
     */
    #[Assert\NotBlank(message: 'contact.firstname.not_blank')]
    #[Assert\Length(min: 3, max: 50, minMessage: 'contact.firstname.min_message', maxMessage: 'contact.firstname.max_message')]
    protected ?string $firstname;

    /**
     * @var string|null
     */
    #[Assert\NotBlank(message: 'contact.lastname.not_blank')]
    #[Assert\Length(min: 3, max: 50, minMessage: 'contact.lastname.min_message', maxMessage: 'contact.lastname.max_message')]
    protected ?string $lastname;

    /**
     * @var string|null
     */
    #[Assert\NotBlank(message: 'contact.email.not_blank')]
    #[Assert\Email(message: 'contact.email.error')]
    protected ?string $contactEmail;

    /**
     * @var string|null
     */
    #[Assert\NotBlank(message: 'contact.message.not_blank')]
    #[Assert\Length(min: 10, max: 512, minMessage: 'contact.message.min_message', maxMessage: 'contact.message.max_message')]
    protected ?string $contactMessage;

    /**
     * @var bool
     */
    protected bool $contactValid;

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(?string $firstname): ContactEmail
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(?string $lastname): ContactEmail
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getContactEmail(): ?string
    {
        return $this->contactEmail;
    }

    public function setContactEmail(?string $contactEmail): ContactEmail
    {
        $this->contactEmail = $contactEmail;

        return $this;
    }

    public function getContactMessage(): ?string
    {
        return $this->contactMessage;
    }

    /**
     * @return bool|null
     */
    public function isContactValid(): ?bool
    {
        return $this->contactValid;
    }

    public function setContactMessage(?string $contactMessage): ContactEmail
    {
        $this->contactMessage = $contactMessage;

        return $this;
    }

    public function setContactValid(bool $contactValid): ContactEmail
    {
        $this->contactValid = $contactValid;

        return $this;
    }
}
