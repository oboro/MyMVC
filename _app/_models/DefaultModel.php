<?php
/**
 * モデル クラス
 * @access public
 * @version 1.00
 * @since 2018/06/19
 */
class DefaultModel extends Model
{
    /** @var array	バリデーション規則 */
    protected $validates = array
    (
        # form.name
        'default_form' => array
        (
            # 名前
            'name_last' => array
            (
                # バリデーション規則：必須入力 && 6字以内
                'rules' => array( 'is' => true, 'str_less_than' => 6, ),
                # 入力エラーの場合のメッセージ
                'message' => 'お名前(姓)を6文字以内で入力してください。',
            ),
            'name_first' => array
            (
                # バリデーション規則：必須入力 && 6字以内
                'rules' => array( 'is' => true, 'str_less_than' => 6, ),
                # 入力エラーの場合のメッセージ
                'message' => 'お名前(名)を6文字以内で入力してください。',
            ),
            # 電話番号
            'tel' => array
            (
                # バリデーション規則：必須入力なし && 正規表現(半角数字) && 10-11字
                'rules' => array( 'is' => true, 'with' => '/^[0-9]{10,11}$/', ),
                'message' => '電話番号を半角数字のみで入力してください。',
            ),
            
            # Email
            'email' => array
            (
                # バリデーション規則：必須入力なし && 正規表現(email) && 7字以上
                'rules' => array( 'is' => true, 'with' => '/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/', 'str_more_than' => 7, 'str_less_than' => 50,),
                'message' => 'emailアドレスは7字以上のyour-address@yourdomain.comの形式で入力してください。',
            ),
        ),
        # 他のform.name ここにもバリデーションを設定したい場合は、上記の形式の記述を繰り返します。
        'default_other_form' => array
       (
            
        ),
    );
    
    /**
     * コンストラクタ
     * @access public
     * @param  void
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->db = Db::get();
    }
    
    
    public function get_tables()
    {
        return $this->db->get_result( $this->db->do_query("SHOW TABLES;") );
    }

}
?>
