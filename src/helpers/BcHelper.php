<?php

namespace davidxu\base\helpers;

/**
 * BC Math Functions
 *
 * Class BcHelper
 * @package davidxu\base\helpers
 */
class BcHelper
{
    /**
     * Add two arbitrary precision numbers
     *
     * @param float|int|string $left_operand The left operand, as a string.
     * @param float|int|string $right_operand The right operand, as a string.
     * @param ?int $scale This optional parameter is used to set the number of digits after the decimal place in the result. If omitted, it will default to the scale set globally with the [[bcscale()]] function, or fallback to 0 if this has not been set.
     * @return string
     */
    public static function add(float|int|string $left_operand, float|int|string $right_operand, ?int $scale = 2): string
    {
        return bcadd($left_operand, $right_operand, $scale);
    }

    /**
     * Compare two arbitrary precision numbers
     *
     * @param string|float|int $left_operand The left operand, as a string.
     * @param string|float|int $right_operand The right operand, as a string.
     * @param ?int $scale This optional parameter is used to set the number of digits after the decimal place in the result. If omitted, it will default to the scale set globally with the [[bcscale()]] function, or fallback to 0 if this has not been set.
     * @return int
     */
    public static function comp(string|float|int $left_operand, string|float|int $right_operand, ?int $scale = 2): int
    {
        return bccomp($left_operand, $right_operand, $scale);
    }

    /**
     * Divide two arbitrary precision numbers
     * Returns the result of the division as a string, or null if [[divisor]] is 0.
     *
     * @param string|float|int $dividend The dividend, as a string.
     * @param string|float|int $divisor The divisor, as a string.
     * @param ?int $scale This optional parameter is used to set the number of digits after the decimal place in the result. If omitted, it will default to the scale set globally with the [[bcscale()]] function, or fallback to 0 if this has not been set.
     * @return ?string
     */
    public static function div(string|float|int $dividend, string|float|int $divisor, ?int $scale = 2): ?string
    {
        return bcdiv($dividend, $divisor, $scale);
    }

    /**
     * Get modulus of an arbitrary precision number
     * Returns the modulus as a string, or null if [[divisor]] is 0.
     *
     * @param string|float|int $dividend The dividend, as a string.
     * @param string|float|int $divisor The divisor, as a string.
     * @param ?int $scale This optional parameter is used to set the number of digits after the decimal place in the result. If omitted, it will default to the scale set globally with the [[bcscale()]] function, or fallback to 0 if this has not been set.
     * @return string|null
     */
    public static function mod(string|float|int $dividend, string|float|int $divisor, ?int $scale = 2): ?string
    {
        return bcmod($dividend, $divisor, $scale);
    }

    /**
     * Multiply two arbitrary precision numbers
     *
     * @param string|float|int $left_operand The left operand, as a string.
     * @param string|float|int $right_operand The right operand, as a string.
     * @param ?int $scale This optional parameter is used to set the number of digits after the decimal place in the result. If omitted, it will default to the scale set globally with the [[bcscale()]] function, or fallback to 0 if this has not been set.
     * @return string
     */
    public static function mul(string|float|int $left_operand, string|float|int $right_operand, ?int $scale = 2): string
    {
        return bcmul($left_operand, $right_operand, $scale);
    }

    /**
     * Raise an arbitrary precision number to another
     * Raise [[base]] to the power [[exponent]]
     *
     * @param string|float|int $base The base, as a string.
     * @param string|float|int $exponent The exponent, as a string. If the exponent is non-integral, it is truncated. The valid range of the exponent is platform specific, but is at least -2147483648 to 2147483647.
     * @param ?int $scale This optional parameter is used to set the number of digits after the decimal place in the result. If omitted, it will default to the scale set globally with the [[bcscale()]] function, or fallback to 0 if this has not been set.
     * @return string
     */
    public static function pow(string|float|int $base, string|float|int $exponent, ?int $scale = 2): string
    {
        return bcpow($base, $exponent, $scale);
    }

    /**
     * Raise an arbitrary precision number to another, reduced by a specified modulus
     *
     * @param string|int $base The base, as an integral string (i.e. the scale has to be zero).
     * @param string|int $exponent The exponent, as a non-negative, integral string (i.e. the scale has to be zero).
     * @param string|int $modulus The modulus, as an integral string (i.e. the scale has to be zero).
     * @param ?int $scale This optional parameter is used to set the number of digits after the decimal place in the result. If omitted, it will default to the scale set globally with the [[bcscale()]] function, or fallback to 0 if this has not been set.
     *
     * @return string|false
     */
    public static function powmod(string|int $base,
                                  string|int $exponent,
                                  string|int $modulus,
                                  ?int $scale = 2): string|false
    {
        return bcpowmod($base, $exponent, $modulus, $scale);
    }

    /**
     * Set or get default scale parameter for all bc math functions
     * Gets the current scale factor.
     *
     * @param ?int $scale The scale factor.
     * @return int
     */
    public static function scale(?int $scale): int
    {
        return bcscale($scale);
    }

    /**
     * Get the square root of an arbitrary precision number
     * Return the square root of the [[number]].
     *
     * @param string|float|int $number The operand, as a well-formed BCMath numeric string.
     * @param null|int $scale This optional parameter is used to set the number of digits after the decimal place in the result. If omitted, it will default to the scale set globally with the [[bcscale()]] function, or fallback to 0 if this has not been set.
     * @return string
     */
    public static function sqrt(string|float|int $number, ?int $scale = null): string
    {
        return bcsqrt($number, $scale);
    }

    /**
     * Subtract one arbitrary precision number from another
     * Subtracts the [[right_operand]] from the [[left_operand]].
     *
     * @param string|float|int $left_operand The left operand, as a string.
     * @param string|float|int $right_operand The right operand, as a string.
     * @param ?int $scale This optional parameter is used to set the number of digits after the decimal place in the result. If omitted, it will default to the scale set globally with the [[bcscale()]] function, or fallback to 0 if this has not been set.
     * @return string
     */
    public static function sub(string|float|int $left_operand, string|float|int $right_operand, ?int $scale = 2): string
    {
        return bcsub($left_operand, $right_operand, $scale);
    }

    /**
     * Rounds a float
     *
     * @param string|float|int $num The value to round.
     * @param int $precision The optional number of decimal digits to round to.
     * If the [[precision]] is positive, [[num]] is rounded to [[precision]] significant digits after the decimal point.
     * If the [[precision]] is negative, [[num]] is rounded to [[precision]] significant digits before the decimal point, i.e. to the nearest multiple of pow(10, -precision), e.g. for a [[precision]] of -1 [[num]] is rounded to tens, for a [[precision]] of -2 to hundreds, etc.
     * @param int $mode Use one of the following constants to specify the mode in which rounding occurs.
     * PHP_ROUND_HALF_UP: Rounds [[num]] away from zero when it is half way there, making 1.5 into 2 and -1.5 into -2.
     * PHP_ROUND_HALF_DOWN: Rounds [[num]] towards zero when it is half way there, making 1.5 into 1 and -1.5 into -1.
     * PHP_ROUND_HALF_EVEN: Rounds [[num]] towards the nearest even value when it is half way there, making both 1.5 and 2.5 into 2.
     * PHP_ROUND_HALF_ODD: Rounds [[num]] towards the nearest odd value when it is half way there, making 1.5 into 1 and 2.5 into 3.
     * @return float The value rounded to the given [[precision]] as a float.
     */
    private static function round(string|float|int $num,
                                  int              $precision = 0,
                                  int              $mode = PHP_ROUND_HALF_UP): float
    {
        return round($num, $precision, $mode);
    }
}
