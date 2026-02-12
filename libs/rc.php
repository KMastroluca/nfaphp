<?php

class RC4{

    private $base64 = TRUE;
    private $key;
    private $isRandomKey = FALSE;

    public function __construct( $key ) {
        
        $this->key = $key;
        
    }
    
    private function crypt($string){
        
        for( $i=0,$c; $i<256; $i++){

            $c[$i]=$i;

        }

        for( $i=0, $d = NULL, $e = NULL, $g = strlen( $this->key ); $i < 256; $i++ ){
            
            $d=($d+$c[$i]+ord($this->key[$i%$g]))%256;
            $e=$c[$i];
            $c[$i]=$c[$d];
            $c[$d]=$e;
            
        }
        
        for( $y=0,$i=0,$d=0,$f = NULL;$y<strlen($string);$y++){
            
            $i      = ($i+1)%256;
            $d      = ($d+$c[$i])%256;
            $e      = $c[$i];
            $c[$i]  = $c[$d];
            $c[$d]  = $e;
            $f      .= chr( ord( $string[$y] ) ^ $c[($c[$i]+$c[$d])%256] );
        
        }
        
        return $f;
        
    }
    
    public function setRandomKey( $value ) {
        
        $this->isRandomKey = ( bool ) $value;
        return $this;
        
    }
    
    public function Decrypt( $string ){
        
        if( $this->isRandomKey ){
            
            $this->key .= substr( $string, 0, 5 );
            $string = substr( $string, 5 );
            
        }
        
        return $this->crypt( ( $this->base64 ? base64_decode( $string ) : $string ) );
        
    }
    
    public function Encrypt( $string ){
        
        $addToString = '';
        
        if( $this->isRandomKey ){
            
            $addToString = substr( time(), -5 );
            $this->key .= $addToString;
            
        }
        
        $result = $this->crypt( $string );
        
        return $addToString . ( $this->base64 ? base64_encode( $result ) : $result ); 
        
    }
    
}
