# Debug Bar Logger
1. Activate Debug Bar
2. Activate this plugin
3. In your plugins, use one of the following:

		# Usage 1: As a logging function
        rdbg_log( 'debug', 'This is my message', array('Data to pass as context (must be array)') );

        # Usage 2: As a PSR-3 compatible logger
        $logger = rdbg_logger();
        $logger->debug('This is my message', array('Data to pass as context (must be array)'));

The log level should be one of the LogLevel constants from PSR-3:

    use Psr\Log\LogLevel;
    // LogLevel::EMERGENCY = 'emergency';
    // LogLevel::ALERT = 'alert';
    // LogLevel::CRITICAL = 'critical';
    // LogLevel::ERROR = 'error';
    // LogLevel::WARNING = 'warning';
    // LogLevel::NOTICE = 'notice';
    // LogLevel::INFO = 'info';
    // LogLevel::DEBUG = 'debug';
