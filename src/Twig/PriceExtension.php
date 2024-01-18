<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class PriceExtension extends AbstractExtension
{
    public function getFilters()
    {
        return [
            new TwigFilter('price', [$this, 'convertToEuros']),
        ];
    }

    public function convertToEuros($amount)
    {
        // Assurez-vous que $amount est un nombre avant d'effectuer des opérations
        if (is_numeric($amount)) {
            $amount_in_euros = $amount / 100; // Supposons que le montant est en centimes
            return sprintf('%s€', number_format($amount_in_euros, 2)); // Formatage avec deux décimales
        }
        return $amount; // Si ce n'est pas un nombre, retournez tel quel
    }
}