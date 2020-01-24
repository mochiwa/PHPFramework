<?php
namespace Framework\Validator;
/**
 * The base abstract class for a validator
 *
 * @author mochiwa
 */
interface IValidator {
     function isValid(): bool;
     function getErrors(): array;
}
