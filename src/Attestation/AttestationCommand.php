<?php

declare(strict_types=1);

namespace Jmleroux\CovidAttestation\Attestation;

use Symfony\Component\Validator\Constraints as Assert;

class AttestationCommand
{
    public function __construct()
    {
        $this->date = new \DateTime();
    }

    /**
     * @Assert\NotBlank
     */
    public array $justifications;
    public UserData $userData;
    /**
     * @Assert\NotBlank
     */
    private \DateTime $date;

    public \DateInterval $offset;

    public function getDate()
    {
        $date = clone $this->date;
        $date->sub($this->offset);

        return $date;
    }
}
