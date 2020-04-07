<?php
/**
 * @package pixelbaseWaitlist
 * 
 * @since 1.0.0
 */

namespace PixelBase;

/**
 * Class used to initialize service classes as instantiated objects.
 *
 * @since 1.0.0
 */
final class Init
{
    /**
     * Stores all the classes in an array.
     * 
     * @since 1.0.0
     * 
     * @return array Full list of classes
     */
    public static function getServices() 
    {
        return [
            Services\Enqueue::class,
            Services\SettingsLink::class,
            Services\AdminPages::class,
            Services\FormSettings::class,
            Services\MailSettings::class,
            Services\UnsubscribeSettings::class,
            Services\CsvExport::class,
            Services\PostTypes::class,
            Services\SubscriberStatus::class,
            Services\Actions::class,
            Services\Filter::class,
            Services\Mail::class,
            Services\WaitlistForm::class,
            Services\Unsubscribe::class
        ];
    }

    /**
     * Loops through the classes, initializes them,
     * and calls the register() method if it exists
     * in the class.
     * 
     * @since 1.0.0
     * 
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
     * Instantiates the class.
     * 
     * @since 1.0.0
     * 
     * @param class $class Class from the get_services array 
     * @return class instance New instance of the class.
     */
    private static function instantiate($class) 
    {
        $service = new $class();
        return $service;
    }
}
