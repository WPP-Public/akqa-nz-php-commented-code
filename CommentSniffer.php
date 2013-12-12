<?php


class CommentSniffer {


    protected static $token_values = array (
        T_AND_EQUAL  => 5, // &=  assignment operators
        T_ARRAY  => 5, //array() array(), array syntax
        T_ARRAY_CAST  => 7, // (array) type-casting
        T_BOOLEAN_AND  => 5, //  &&  logical operators
        T_BOOLEAN_OR  => 5, //   ||  logical operators
        T_BOOL_CAST  => 7, //(bool) or (boolean) type-casting
        T_CLASS  => 3, //class   classes and objects
        T_CLASS_C  => 10, //  __CLASS__   magic constants (available since PHP 4.3.0)
        T_CLONE  => 5, //clone   classes and objects (available since PHP 5.0.0)
        T_CLOSE_TAG  => 5, //
        T_CONCAT_EQUAL  => 3, // .=  assignment operators
        T_CONST  => 3, //const   class constants
        T_CONSTANT_ENCAPSED_STRING  => 1, // "foo" or 'bar'  string syntax
        T_DIR  => 10, //  __DIR__ magic constants (available since PHP 5.3.0)
        T_DOUBLE_ARROW  => 7, // =>  array syntax
        T_DOUBLE_CAST  => 7, //  (real), (double) or (float) type-casting
        T_DOUBLE_COLON  => 7, // ::  see T_PAAMAYIM_NEKUDOTAYIM  => 5, //below
        T_ECHO  => 3, // echo    echo
        T_ELSE  => 3, // else    else
        T_ELSEIF  => 3, //   elseif  elseif
        T_EMPTY  => 5, //empty   empty()
        T_ENCAPSED_AND_WHITESPACE  => 3, //  " $a"   constant part of string with variables
        T_ENDFOR  => 5, //   endfor  for, alternative syntax
        T_ENDFOREACH  => 5, //   endforeach  foreach, alternative syntax
        T_ENDIF  => 5, //endif   if, alternative syntax
        T_ENDSWITCH  => 5, //endswitch   switch, alternative syntax
        T_ENDWHILE  => 5, // endwhile    while, alternative syntax
        T_END_HEREDOC  => 5, //      heredoc syntax
        T_EVAL  => 7, // eval()  eval()
        T_EXIT  => 5, // exit or die exit(), die()
        T_EXTENDS  => 3, //  extends extends, classes and objects
        T_FILE  => 10, // __FILE__    magic constants
        T_FOR  => 1, //  for for
        T_FOREACH  => 7, //  foreach foreach
        T_FUNCTION  => 8, // function or cfunction   functions
        T_FUNC_C  => 10, //   __FUNCTION__    magic constants (available since PHP 4.3.0)
        T_IF  => 1, //   if  if
        T_IMPLEMENTS  => 5, //   implements  Object Interfaces (available since PHP 5.0.0)
        T_INCLUDE  => 5, //  include()   include
        T_INCLUDE_ONCE  => 7, // include_once()  include_once
        T_INSTANCEOF  => 7, //   instanceof  type operators (available since PHP 5.0.0)
        T_INT_CAST  => 7, // (int) or (integer)  type-casting
        T_INTERFACE  => 3, //interface   Object Interfaces (available since PHP 5.0.0)
        T_ISSET  => 5, //isset() isset()
        T_IS_EQUAL  => 3, // ==  comparison operators
        T_IS_GREATER_OR_EQUAL  => 3, //  >=  comparison operators
        T_IS_IDENTICAL  => 5, // === comparison operators
        T_IS_NOT_EQUAL  => 5, // != or <>    comparison operators
        T_IS_NOT_IDENTICAL  => 5, // !== comparison operators
        T_IS_SMALLER_OR_EQUAL  => 5, //  <=  comparison operators
        T_LINE  => 10, // __LINE__    magic constants
        T_METHOD_C  => 10, // __METHOD__  magic constants (available since PHP 5.0.0)
        T_MINUS_EQUAL  => 5, //  -=  assignment operators
        T_MOD_EQUAL  => 5, //%=  assignment operators
        T_MUL_EQUAL  => 5, //*=  assignment operators
        T_NAMESPACE  => 5, //namespace   namespaces (available since PHP 5.3.0)
        T_NS_C  => 10, // __NAMESPACE__   namespaces (available since PHP 5.3.0)
        T_NEW  => 1, //  new classes and objects
        T_OBJECT_CAST  => 7, //  (object)    type-casting
        T_OBJECT_OPERATOR  => 5, //  ->  classes and objects
        T_OPEN_TAG_WITH_ECHO  => 7, //   <?= or <%=  escaping from HTML
        T_PAAMAYIM_NEKUDOTAYIM  => 7, // ::  ::. Also defined as T_DOUBLE_COLON.
        T_PLUS_EQUAL  => 5, //   +=  assignment operators
        T_PRINT  => 5, //print() print
        T_PRIVATE  => 3, //  private classes and objects (available since PHP 5.0.0)
        T_PUBLIC  => 3, //   public  classes and objects (available since PHP 5.0.0)
        T_PROTECTED  => 3, //protected   classes and objects (available since PHP 5.0.0)
        T_REQUIRE  => 5, //  require()   require
        T_REQUIRE_ONCE  => 7, // require_once()  require_once
        T_RETURN  => 1, //   return  returning values
        T_START_HEREDOC  => 5, //<<< heredoc syntax
        T_STATIC  => 3, //   static  variable scope
        T_STRING_CAST  => 7, //  (string)    type-casting
        T_STRING_VARNAME  => 5, //   "${a    complex variable parsed syntax
        T_SWITCH  => 5, //   switch  switch
        T_THROW  => 3, //throw   Exceptions (available since PHP 5.0.0)
        T_TRAIT  => 3, //trait   Traits (available since PHP 5.4.0)
        T_TRAIT_C  => 10, //  __TRAIT__  => 5, //  __TRAIT__  => 5, //(available since PHP 5.4.0)
        T_TRY  => 5, //  try Exceptions (available since PHP 5.0.0)
        T_UNSET  => 5, //unset() unset()
        T_UNSET_CAST  => 7, //   (unset) type-casting (available since PHP 5.0.0)
        T_USE  => 1, //  use namespaces (available since PHP 5.3.0; reserved since PHP 4.0.0)
        T_VARIABLE  => 3, // $foo    variables
        T_WHILE  => 1, //while   while, do..while
        T_XOR_EQUAL  => 5, //^=  assignment operators
        T_YIELD  => 1, //yield   generators (available since PHP 5.5.0)
    );


    protected $files;


    protected $tolerance;


    public function __construct($dir, $tolerance = 5)
    {
        if(!is_dir($dir)) {
            throw new RuntimeException("Invalid directory: $dir");
        }

        $phpFiles = new RegexIterator(
            new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator($dir)
            ),
            '/^.+\.php$/i',
            RecursiveRegexIterator::GET_MATCH
        );

        if(!$phpFiles->getSize()) {
            writeOut("There are no .php files in this directory tree.");
            exit;
        }

        $this->files = $phpFiles;
        $this->tolerance = $tolerance;
    }


    public function run()
    {
        foreach($this->files as $filename => $list) {
            $fileScores = array ();
            foreach(token_get_all(file_get_contents($filename)) as $token) {
                if($token[0] !== T_COMMENT) continue;
                $comment = $token[1];

                $comment = preg_replace('/^\/\*\*?/', '', $comment);
                $comment = preg_replace('/\*\/$/','', $comment);
                $comment = preg_replace('/\/\//','', $comment);

                $contents = token_get_all("<?php $comment ?>");
                $score = 0;
                foreach($contents as $subToken) {
                    if(isset(self::$token_values[$subToken[0]])) {
                        $score += self::$token_values[$subToken[0]];
                    }
                    else {
                        $score--;
                    }
                }
                if($score >= $this->tolerance) {
                    $fileScores[$token[2]] = $score;
                }
            }

            if(!empty($fileScores)) {
                writeOut("File: $filename");
                foreach($fileScores as $line => $score) {
                    writeOut("\tLine $line [score: $score]");
                }
            }
        }
    }
}