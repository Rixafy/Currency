# Currency
💱 CRUD model in PHP using Doctrine ORM

# Installation
```
composer require rixafy/currency
```

# Example usage

Basic examples for working with extension, you need to register services to DI or use [this integration to nette framework](https://github.com/Archette/Currency)

## Converting
```PHP
$eur = $this->currencyFacade->getByCode('EUR'); // returns Currency instance
$usd = $this->currencyFacade->getByCode('USD'); // returns Currency instance

$eur->convertFrom($usd, 100); // converts 100 USD to EUR, returns float
$eur->convertTo($usd, 100); // converts 100 EUR to USD, returns float
```

## Formatting
```PHP
echo $this->currencyFacade->getByCode('USD')->formatToNumber(45.54); // returns 45.54
echo $this->currencyFacade->getByCode('EUR')->formatToNumber(45.54); // returns 45,54
echo $this->currencyFacade->getByCode('USD')->formatToString(45.54); // returns $45.54
echo $this->currencyFacade->getByCode('EUR')->formatToString(45.54); // returns 45,54 €
```

decimal point, hundred and thousand separator, symbol and code is saved in DB (table currency)

# Important

Extension requires Doctrine ORM and symfony\console for currency import.
