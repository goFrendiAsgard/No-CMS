<?php

if(! function_exists('cms_init_controller'))
{
    function cms_init_controller($class, $namespace, $filePath){
        $sourceCode = file_get_contents($filePath);
        $sourceCode = trim($sourceCode);
        $sourceCode = trim($sourceCode, '<?php');
        $sourceCode = trim($sourceCode, '?>');

        // add namespace if necessary
        if(strpos($sourceCode, 'namespace') !== FALSE)
        {
            $sourceCode = 'namespace '.$namespace.';'.PHP_EOL;
        }

        // extends things with CMS_ prefix?
        if(strpos($sourceCode, 'extends CMS_') !== FALSE)
        {
            $sourceCode = str_replace('extends CMS_', 'extends \\CMS_', $sourceCode);
        }

        // evaluate source code
        eval($sourceCode);
        eval('$obj = new '.$nameSpace.'\\'.$class.'()');
        return $obj;
    }
}

// Run controller
if(! function_exists('cms_run_controller'))
{
    function cms_run_controller($url)
    {
        $url = trim($url, '/');

        // get parts of url, get, and namespace
        $urlPart = explode('?', $url);
        $getPart = array();
        $namespacePart = array();
        if(count($urlPart) > 1)
        {
            $getPart = explode('&', $urlPart[1]);
            $newGetPart = array();
            foreach($getPart as $getPair)
            {
                $getPair = explode('=', $getPair);
                $newGetPart[$getPair[0]] = $getPair[1];
            }
            $getPart = $newGetPart;
        }
        $urlPart = explode('/', $urlPart[0]);
        foreach($urlPart as $part){
            $namespacePart[] = ucfirst($part);
        }

        // save old $_GET
        $oldGet = $_GET;
        foreach($getPart as $key => $val)
        {
            $_GET[$key] = $val;
        }

        // get module list
        $moduleList = array();
        foreach(scandir(FCPATH.'modules/') as $dirPath)
        {
            if(is_dir($dirPath))
            {
                $moduleList[] = $dirPath;
            }
        }

        $namespace = '';
        $filePath = '';
        $class = '';
        $function = '';
        $parameter = '';
        for($i=0; $i<count($urlPart); $i++)
        {
            // normal scenario (without module)
            $mainFilePath = APPPATH . 'controllers/' . implode('/', array_slice($urlPart,0, $i));
            $mainFilePath = rtrim($mainFilePath, '/') . '/';
            $mainClass = $i < count($urlPart)? ucfirst($urlPart[$i]) : '';
            $mainFilePath .= $mainClass.'.php';
            $mainFunction = $i+1 < count($urlPart)? $urlPart[$i+1] : 'index';
            $mainParameter = $i+2 < count($urlPart)? array_slice($urlPart, $i+2): array();
            $mainnamespace = '\\App\\Controllers\\' . implode('\\', array_slice($namespacePart, 0, $i));

            // HMVC scenario
            $moduleName = $urlPart[0];
            $moduleFilePath = FCPATH.  'modules/' . $moduleName . '/controllers/' . implode('/', array_slice($urlPart, 1, $i));
            $moduleFilePath = rtrim($moduleFilePath, '/') . '/';
            $modulenamespace = '\\Modules\\' . ucfirst($moduleName) . '\\Controllers\\' . implode('\\', array_slice($namespacePart, 0, $i));
            // HMVC scenario 1
            $moduleClass1 = $i+1 < count($urlPart)? ucfirst($urlPart[$i+1]) : ucfirst($moduleName);
            $moduleFilePath1 = $moduleFilePath . $moduleClass1.'.php';
            $moduleFunction1 = $i+2 < count($urlPart)? $urlPart[$i+2] : 'index';
            $moduleParameter1 = $i+3 < count($urlPart)? array_slice($urlPart, $i+3): array();
            // HMVC scenario 2
            $moduleClass2 = $mainClass;
            $moduleFilePath2 = $moduleFilePath . $mainClass.'.php';
            $moduleFunction2 = $mainFunction;
            $moduleParameter2 = $mainParameter;

            if(file_exists($moduleFilePath1) && is_readable($moduleFilePath1))
            {
                $filePath = $moduleFilePath1;
                $class = $moduleClass1;
                $function = $moduleFunction1;
                $parameter = $moduleParameter1;
                $namespace = $modulenamespace;
                break;
            }
            else if(file_exists($moduleFilePath2) && is_readable($moduleFilePath2))
            {
                $filePath = $moduleFilePath2;
                $class = $moduleClass2;
                $function = $moduleFunction2;
                $parameter = $moduleParameter2;
                $namespace = $modulenamespace;
                break;
            }
            else if(file_exists($mainFilePath) && is_readable($mainFilePath))
            {
                $filePath = $mainFilePath;
                $class = $mainClass;
                $function = $mainFunction;
                $parameter = $mainParameter;
                $namespace = $mainnamespace;
                break;
            }
        }
        
        $obj = cms_init_controller($class, $namespace, $filePath);
        if(method_exists($obj, $function)){
            // TODO : complete this
        }

        // reset the $_GET
        $_GET = $oldGet;
    }
}

// init autoload
if(! function_exists('cms_init_autoload'))
{
    function cms_init_autoload(){
        // simply call autoload
        spl_autoload_register(
            function($class)
            {
                $classParts = explode('\\', trim($class, '\\'));

                // Modules
                if(count($classParts) > 1 && ($classParts[0] == 'Modules' || $classParts[0] == 'App'))
                {
                    $classParts = array_slice($classParts, 1);
                    for($i=0; $i<count($classParts); $i++)
                    {
                        if($i < count($classParts)-1)
                        {
                            $classParts[$i] = lcfirst($classParts[$i]);
                        }
                        else
                        {
                            $classParts[$i] = $classParts[$i] . '.php';
                        }
                    }
                    $fileName = ($classParts[0] == 'Modules'? FCPATH.'modules/' : APPPATH) . 
                        implode('/', $classParts);
                    if(file_exists($fileName) && is_readable($fileName))
                    {
                        include($fileName);
                    }
                }

            }
        );
    }
}
