<?php
/**
 * Created by JetBrains PhpStorm.
 * User: tiejia
 * Date: 12-4-21
 * Time: 下午8:22
 * To change this template use File | Settings | File Templates.
 */

class Controller_Db extends Extension_Controller_Base
{


    public function actionInsertTest(){

        $userTable = $this->tableDb("users");

        $insertArray = array("user_id"=>Bowl::uuid(),"user_name"=>"赵铁甲，赵铁甲");

        $insert = $userTable->insert($insertArray,false);

        if($insert === false){
            echo "insert false";
        }else{
            echo "insert success";
        }
    }


    public function actionSelect()
    {
        //---------------------------------
        // 简单查询
        //---------------------------------
        //获取User表的Table对象
        $tableUser = $this->tableDb("user");
        //查询所有用户
        $users = $tableUser->find();
        dump($users);

        //---------------------------------
        // 带条件查询
        //---------------------------------
        $uid = "1";
        //生成where语句，生成结果为 WHERE user_id = '1'
        $where = $tableUser->where("user_id = %s", $uid);
        //执行查询,只获取首行，返回为一维数组
        $user = $tableUser->findRow("*", $where);
        dump($user);

        //---------------------------------
        // Like查询
        //---------------------------------
        $uName = "Armor";
        //生成where语句，生成结果为 WHERE user_id = '1'
        $where = $tableUser->where("user_name like %s", "%$uName%");
        //执行查询
        $users = $tableUser->find("*", $where);
        dump($users);

        //---------------------------------
        // 分页和排序
        //---------------------------------
        $uName = "Armor";
        //生成where语句，生成结果为 WHERE user_id = '1'
        $where = $tableUser->where("user_name like %s", "%$uName%");
        //使用user_createtime 降序排序
        $sort = array("key" => "user_createtime", "order" => "DESC");
        //从0开始取10条记录
        $page = array("start" => 0, "limit" => 10);
        //执行查询
        $users = $tableUser->find("*", $where, $sort, $page);
        dump($users);

        //---------------------------------
        // 直接执行SQL
        // 当需要有直接执行SQL的情况，使用ActiveDb，
        // 因为TableDb对自适应数据的分页没有做支持
        //---------------------------------
        $sql = "select * from user where user_name like ? ";
        $users = $tableUser->execQuery($sql, array("%Armor%"));

    }

    public function actionInsert()
    {
        //----------------------------------
        // Insert操作
        //----------------------------------
        //获取User表的Table对象
        $tableUser = $this->tableDb("user");
        //要插入数据库的字段
        $insertArray = array(
            "user_id" => Bowl::uuid(),
            "user_name" => "Armor",
            "user_createtime" => date("YmdHis")
        );
        //执行SQL,成功的时候返回的是主键值
        $id = $tableUser->insert($insertArray);
        //判断时必须使用===操作符
        if (false === $id) {
            echo "执行insert 失败";
        } else {
            echo "执行成功！";
        }
    }

    public function actionDelete(){

        //----------------------------------
        // Delete操作
        //----------------------------------
        //获取User表的Table对象
        $tableUser = $this->tableDb("user");

        //执行SQL,成功的时候返回的是主键值
        $where = $tableUser->where("user_id = %s","Armor");
        $rowCount = $tableUser->delete($where);
        //判断时必须使用===操作符
        if (false === $rowCount) {
            echo "执行delete 失败";
        } else {
            echo "执行成功！";
        }


    }


    public function actionUpdate()
    {
        //----------------------------------
        // Insert操作
        //----------------------------------
        //获取User表的Table对象
        $tableUser = $this->tableDb("user");
        //要插入数据库的字段
        $updateArray = array(
            "user_name" => "Ebupt"
        );
        //生成WHERE语句
        $where = $tableUser->where("user_id = %s","123456");
        //执行SQL，返回值是update的行数
        $rowCount = $tableUser->update($updateArray,$where);
        //判断时必须使用===操作符
        if (false === $rowCount) {
            echo "执行update失败";
        } else {
            echo "执行成功，共更新".$rowCount."行";
        }
    }


}