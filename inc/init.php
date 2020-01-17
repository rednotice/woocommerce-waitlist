<?php
/**
 * @package wpbitsWaitlist
 */

namespace Inc;

final class Init
{
    /**
     * Store all the classes in an array.
     * @return array Full list of classes
     */
    public static function getServices() 
    {
        return [
            Base\Enqueue::class,
            Base\SettingsLink::class,
            Services\AdminPages::class,
            Services\FormSettings::class,
            Services\MailSettings::class,
            Services\PostTypes::class,
            Services\Action::class,
            Services\Filter::class,
            Services\Mail::class,
            Services\WaitlistForm::class
        ];
    }

    /**
     * Loop through the classes, initialize them,
     * and call the register() method if it exists
     * in the class.
     * @return void
     */
    public static function registerServices() 
    {
        foreach(self::getServices() as $class) {
            $service = self::instantiate($class);
            if(method_exists($service, 'register')) {
                $service->register();
            }
        }
    }

    /**
     * Initialize the class.
     * @param class $class Class from the get_services array 
     * @return class instance New instance of the class.
     */
    private static function instantiate($class) 
    {
        $service = new $class();
        return $service;
    }
}