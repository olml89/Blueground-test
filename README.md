<p align="center"><a href="https://www.theblueground.com" target="_blank"><img src="https://cdn.theblueground.com/website/static/img/logo-icon-wordmark-blue-main.73a1a419e72841d9c990.svg" width="600" alt="Blueground"></a></p>

<p align="center">
<a href="https://opensource.org/licenses/MIT"><img src="https://img.shields.io/badge/License-MIT-green.svg" alt="License"></a>
</p>

# Specification

Implement the use case of a vending machine which accepts the following coins:
* 1 cent
* 5 cents
* 10 cents
* 25 cents

And can dispatch the following products:
* **Water**: 25 cents
* **Soda**: 35 cents
* **Snack**: 45 cents

You have to take especially into account:
* You can not provide a product which does not exist on the machine
* The machine has to provide change, using the highest coins possible
* If the needed change cannot be provided with the coins on the machine's bucket, the product cannot be purchased and the original coins must be returned
