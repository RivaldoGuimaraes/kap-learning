<?php
/*Oraculum::Load('Models');
Oraculum::Load('DBO');*/
class ActiveRecord extends DBO{
    private $_fields=array();
    /*protected $_fields = array();
    protected $_keyField = null;*/
    protected $_className=NULL;
    protected $_tableName=NULL;
    protected $_key=array('id');
    protected $_keyvalue=array();
    protected $_types=array('tinyint',
                            'smallint',
                            'mediumint',
                            'int',
                            'bigint',
                            'decimal',
                            'float',
                            'double',
                            'real',
                            'bit',
                            'boolean',
                            'serial',
                            'date',
                            'datetime',
                            'timestamp',
                            'time',
                            'year',
                            'char',
                            'varchar',
                            'tinytext',
                            'text',
                            'mediumtext',
                            'longtext',
                            'binary',
                            'varbinary',
                            'tinyblob',
                            'mediumblob',
                            'blob',
                            'longblob',
                            'enum',
                            'set',
                            'geometry',
                            'point',
                            'linestring',
                            'polygon',
                            'multipoint',
                            'multilinestring',
                            'multipolygon',
                            'geometrycollection');

    public function __construct($class=NULL) {
        if (!is_null($class)) {
            $this->_className=$class;
            $this->_tableName=strtolower($class);
        }
        return $this;
    }
    
    public function getLines($limit=NULL) {
        $limit=(int)$limit;
        $query='SELECT count(*) as \'count\' FROM '.$this->_tableName;
        if ($limit>0) {
            $query.=' LIMIT '.$limit;
        }
        $rows=$this->execSQL($query);
        $lines=$this->fetch($rows);
        return (int)$lines[0]->count;
    }
    
    public function getAll($limit=NULL, $offset=NULL, $orderby=NULL, $order='ASC') {
        $limit=(int)$limit;
        $offset=(int)$offset;
        $query='SELECT * FROM '.$this->_tableName;
        if (!is_null($orderby)) {
            $query.=' ORDER BY '.$orderby.' '.$order;
        }
        if ($limit>0) {
            $query.=' LIMIT '.$limit;
            if ($offset>0) {
                $query.=' OFFSET '.$offset;
            }
        }
        $rows=$this->execSQL($query);
        return $this->fetch($rows);
    }
    
    public function getFirst($debug=FALSE) {
        $this->updateKeyValue();
        $fields=NULL;
        $eval='$query=sprintf(\'SELECT * FROM '.$this->_tableName.' ';
        $eval.='ORDER BY '.$this->_key[0].' ASC\');';
        if ($debug) {
            echo $eval;
        }
        eval($eval);
        $rows=$this->execSQL($query);
        $rows=$this->fetch($rows);
        if (sizeof($rows)>0) {
            return $rows[0];
        } else {
            return NULL;
        }
    }
    
    public function getLast($debug=FALSE) {
        $this->updateKeyValue();
        $fields=NULL;
        $eval='$query=sprintf(\'SELECT * FROM '.$this->_tableName.' ';
        $eval.='ORDER BY '.$this->_key[0].' DESC\');';
        if ($debug) {
            echo $eval;
        }
        eval($eval);
        $rows=$this->execSQL($query);
        $rows=$this->fetch($rows);
        if (sizeof($rows)>0) {
            return $rows[0];
        } else {
            return NULL;
        }
    }
    
    public function getByTableField($field, $value, $type='%s') {
        $query=sprintf('SELECT * FROM '.$this->_tableName.' WHERE '.$field.'="'.$type.'" LIMIT 1',$this->secsql($value));
        $rows=$this->execSQL($query);
        $rows=$this->fetch($rows);
        if (sizeof($rows)>0) {
            return $rows[0];
        } else {
            return NULL;
        }
    }
    
    public function getAllByTableField($field, $value, $type='%s', $limit=NULL, $offset=NULL) {
        $limit=(int)$limit;
        $offset=(int)$offset;
        $sqllimit=NULL;
        if ($limit>0) {
            $sqllimit='LIMIT '.$limit;
            if ($offset>0) {
                $sqllimit.=' OFFSET '.$offset;
            }
        }
        if (is_array($value)) {
            $value='IN ('.implode(',',$value).')';
            $query=sprintf('SELECT * FROM '.$this->_tableName.' WHERE '.$field.' '.$type.' '.$sqllimit, $this->secsql($value));
        } else {
            if (stripos($value, '%')!==FALSE) {
                $query=sprintf('SELECT * FROM '.$this->_tableName.' WHERE '.$field.' LIKE "'.$type.'" '.$sqllimit, $this->secsql($value));
            } else {
                $query=sprintf('SELECT * FROM '.$this->_tableName.' WHERE '.$field.'="'.$type.'" '.$sqllimit, $this->secsql($value));
            }
        }
        $rows=$this->execSQL($query);
        $rows=$this->fetch($rows);
        return $rows;
    }
    
    public function __set($name, $value) {
        if (!is_null($value)) {
            $this->_fields[$name]=$value;
        } else {
            $this->_fields[$name]=NULL;
        }
    }
    
    public function __get($name) {
        if (array_key_exists($name, $this->_fields)) {
            return $this->_fields[$name];
        } else {
            throw new Exception('[Erro CGAR48] Campo \''.$name.'\' inexistente');
        }
    }
    
    public function __call($name, $values) {
        if (stripos($name, 'getBy')!==FALSE) {
            $field=strtolower(str_replace('getBy', '',$name));
            $value=(isset($values[0]))?$values[0]:NULL;
            $type=(isset($values[1]))?$values[1]:'%s';
            return $this->getByTableField($field, $value, $type);
        } elseif (stripos($name, 'getAllBy')!==false) {
            $field=strtolower(str_replace('getAllBy', '',$name));
            $value=(isset($values[0]))?$values[0]:NULL;
            $type=(isset($values[1]))?$values[1]:'%s';
            $limit=(isset($values[2]))?$values[2]:NULL;
            $offset=(isset($values[3]))?$values[3]:NULL;
            return $this->getAllByTableField($field, $value, $type, $limit, $offset);
        }
    }

    public function fetch($rows) {
        $return=array();
        foreach($rows as $row) {
            $obj=new self($this->_className);
            if (!empty($this->_key)) {
                $obj->setKey($this->_key);
            }
            foreach ($row as $field=>$value) {

                if (!is_integer($field)) {
                    $obj->$field=$value;
                }
            }
            $return[]=clone $obj;
        }
        return $return;
    }

    public function insert($debug=FALSE)
    {
        $values=NULL;
        $fields=NULL;
        $eval='$query=sprintf(\'INSERT INTO '.$this->_tableName.' ';
        if (sizeof($this->_fields)>0) {
            foreach ($this->_fields as $field=>$value) {
                if (is_null($values)) {
                    $eval.='(';
                } else {
                    $eval.=',';
                    $values.=',';
                    $fields.=',';
                }
                $eval.=$field;
                $values.='"%s"';
                $fields.='$this->secsql($this->'.$field.')';
            }
        }
        $eval.=')';
        $eval.=' VALUES ('.$values.')\','.$fields.');';
        if ($debug) {
            echo $eval;
        }
        eval($eval);
        $this->execSQL($query, $debug);
        $this->_keyvalue=array($this->getInsertId());
        return $this;
    }
    
    public function update($debug=FALSE) {
        $fields=NULL;
        $eval='$query=sprintf(\'UPDATE '.$this->_tableName.' SET ';
        if (sizeof($this->_fields)>0) {
            foreach ($this->_fields as $field=>$value) {
                if (!is_null($fields)) {
                    $eval.=',';
                }
                if (is_null($value)) {
                    $eval.=$field.'=NULL ';
                    $fields.='';
                } else {
                    $eval.=$field.'="%s" ';
                    $fields.='$this->secsql($this->'.$field.'),';
                }
            }
        }
        $eval.='WHERE '.$this->_key[0].'="%u"';
        $fields.='$this->secsql('.$this->_keyvalue[0].')';
        $eval.='\','.$fields.');';
        if ($debug) {
            echo $eval;
        }
        eval($eval);
        $this->execSQL($query, $debug);
        $this->_keyvalue=array($this->getInsertId());
        return $this;
    }
    
    public function delete($debug=FALSE) {
        $this->updateKeyValue();
        $fields=NULL;
        $eval='$query=sprintf(\'DELETE FROM '.$this->_tableName.' ';
        $eval.='WHERE '.$this->_key[0].'="%u"';
        $fields.='$this->secsql('.$this->_keyvalue[0].')';
        $eval.='\','.$fields.');';
        if ($debug) {
            echo $eval;
        }
        eval($eval);
        $this->execSQL($query);
        return $this;
    }
    
    public function save($validate=TRUE, $exception=TRUE, $debug=FALSE) {
        if ($validate)
            $this->validate($exception);
        $this->updateKeyValue();
        if (!empty($this->_keyvalue)) {
            $this->update($debug);
        } else {
            $this->insert($debug);
        }
    }

    public function setKey($key=array('id')) {
        $this->_key=$key;
        $this->updateKeyValue();
    }

    public function updateKeyValue() {
        $this->_keyvalue=array();
        foreach($this->_key as $key) {
            if (isset($this->_fields[$key])) {
                $this->_keyvalue[]=$this->_fields[$key];
            }
        }
    }
    public function getKeyValue($index=NULL) {
        if (!is_null($index)) {
            return $this->_keyvalue[$index];
        } else {
            return $this->_keyvalue;
        }
    }
    public function secsql($string) {
      //$string=mysql_real_escape_string($string, self::$connection);
      $string=addslashes($string);
      return $string;
    }

    public function validate($exception=TRUE) {
        $valid=TRUE;
        $realfieldlist=array();
        $fields=self::$connection->query('DESC '.$this->_tableName)->fetchAll();
        foreach($fields as $field):
            $realfieldlist[]=$field['Field'];
            $emptyfield=((!isset($this->_fields[$field['Field']]))||(is_null($this->_fields[$field['Field']]))||($this->_fields[$field['Field']]==''));
        
            // Validando NOT NULLs
            if (($field['Extra']!='auto_increment')&&($field['Default']=='')&&(($field['Null']=='NO'))):
                if ($emptyfield):
                    $valid=false;
                    if ($exception):
                        throw new Exception('Campo \''.$field['Field'].'\' n&atilde;o foi preenchido');
                    endif;
                endif;
            endif;
            
            if (!$emptyfield):
                // Validando Tipos
                $types='['.implode('|', $this->_types).']';
                preg_match($types, $field['Type'], $type);
                preg_match('/[0-9]+/', $field['Type'], $size);
                switch ($type):
                    case ($type=='int')||($type=='smallint')||($type=='mediumint')||($type=='bigint')||($type=='bit')||($type=='timestamp'):
                        $this->_fields[$field['Field']]=(int)$this->_fields[$field['Field']];
                        break;
                    case ($type=='float')|| ($type=='decimal')|| ($type=='double'):
                        $this->_fields[$field['Field']]=(float)$this->_fields[$field['Field']];
                        break;
                endswitch;
                
                // Validando Tamanho. Caso exceda o limite o valor é truncado
                if (isset($size[0])):
                    if ((strlen($this->_fields[$field['Field']]))>$size[0]):
                        $this->_fields[$field['Field']]=substr($this->_fields[$field['Field']], 0, $size[0]);
                    endif;
                endif;
                
                // Validando chave estrangeira
                //if ($field['Key']=='MUL'):
                    /*
                      Por motivo de desempenho, a validacao de chave estrangeira deve ser realizada
                      pelo proprio banco e apenas tratada a excessao, e nao pela linguagem para evitar
                      verificacoes desnecessarias que ja sao tratadas pelos gerenciadores de banco de dados */
                //endif;
            endif;
        endforeach;
        foreach ($this->_fields as $field=>$value):
            if (!in_array($field, $realfieldlist)):
                throw new Exception('Campo \''.$field.'\' n&atilde;o encontrado na entidade \''.$this->_tableName.'\'');
            endif;
                    
        endforeach;
    }
}