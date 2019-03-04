<?php

namespace addons\ucenter\controller;

use addons\ucenter\library\Uc;
use app\common\model\User;
use app\common\library\Auth;
use fast\Random;
use think\Cookie;
use think\Hook;
use think\Loader;

/**
 * Ucenter接口,接收来自Ucenter服务器的请求
 *
 */
class Index extends Uc
{

    protected $auth = null;

    public function _initialize()
    {
        //导入UC常量
        Loader::import('uc', ADDON_PATH . 'ucenter');
        parent::_initialize();
        $auth = Auth::instance();
        
        $this->auth = $auth;

        //监听注册登录注销的事件
        Hook::add('user_login_successed', function($user) use($auth) {
            Cookie::set('uid', $user->id);
            Cookie::set('token', $auth->getToken());
        });
        Hook::add('user_register_successed', function($user) use($auth) {
            Cookie::set('uid', $user->id);
            Cookie::set('token', $auth->getToken());
        });
        Hook::add('user_delete_successed', function($user) use($auth) {
            Cookie::delete('uid');
            Cookie::delete('token');
        });
        Hook::add('user_logout_successed', function($user) use($auth) {
            Cookie::delete('uid');
            Cookie::delete('token');
        });

    }

    /**
     * Ucenter接口接收方法
     */
    public function api()
    {
        $this->response();
        return;
    }

    public function index()
    {
        $this->error("当前插件暂无前台页面");
        return;
    }

    /**
     * 删除用户
     */
    protected function deleteuser()
    {
        $uids = $this->get['ids'];
        $uids = is_array($uids) ? $uids : explode(',', $uids);
        !API_DELETEUSER && exit(API_RETURN_FORBIDDEN);
        foreach ($uids as $k => $v)
        {
            $this->auth->delete($v);
        }
        $result = TRUE;
        return $result ? API_RETURN_SUCCEED : API_RETURN_FAILED;
    }

    /**
     * 获取标签
     */
    protected function gettag()
    {
        $name = $this->get['id'];
        if (!API_GETTAG)
        {
            return API_RETURN_FORBIDDEN;
        }
        $datalist = [];
        if ($name == 'get_recently_list')
        {
            for ($i = 0; $i < 9; $i++)
            {
                $datalist[] = array(
                    'name'     => 'name' . $i,
                    'uid'      => 1,
                    'username' => 'username' . $i,
                    'dateline' => '2021',
                    'url'      => 'http://www.yourwebsite.com/thread.php?id=',
                    'image'    => 'http://www.yourwebsite.com/threadimage.php?id=',
                );
            }
        }
        return $this->_serialize([$name, $datalist], 1);
    }

    /**
     * 同步注册
     */
    protected function synregister()
    {
        $uid = $this->get['uid'];
        $username = $this->get['username'];
        $password = $this->get['password'];
        $email = isset($this->get['email']) ? $this->get['email'] : '';
        $mobile = isset($this->get['mobile']) ? $this->get['mobile'] : '';
        if (!API_SYNLOGIN)
        {
            return API_RETURN_FORBIDDEN;
        }
        // 同步注册接口
        header('P3P: CP="CURa ADMa DEVa PSAo PSDo OUR BUS UNI PUR INT DEM STA PRE COM NAV OTC NOI DSP COR"');

        //需实现同步注册的方法
        $this->auth->register($username, $password, $email, $mobile);
        echo 'signup ok';
    }

    /**
     * 同步登录
     */
    protected function synlogin()
    {
        $uid = $this->get['uid'];
        $username = $this->get['username'];
        if (!API_SYNLOGIN)
        {
            return API_RETURN_FORBIDDEN;
        }
        // 同步登录接口
        header('P3P: CP="CURa ADMa DEVa PSAo PSDo OUR BUS UNI PUR INT DEM STA PRE COM NAV OTC NOI DSP COR"');
        //需实现同步登录的方法
        $this->auth->direct($uid);
        echo 'login ok';
    }

    /**
     * 同步退出
     */
    protected function synlogout()
    {
        if (!API_SYNLOGOUT)
        {
            return API_RETURN_FORBIDDEN;
        }

        //同步登出接口
        header('P3P: CP="CURa ADMa DEVa PSAo PSDo OUR BUS UNI PUR INT DEM STA PRE COM NAV OTC NOI DSP COR"');
        //需实现同步登出的方法
        Hook::listen('user_logout_successed');
        echo 'logout ok';
    }

    /**
     * 添加用户
     */
    protected function adduser()
    {
        $uid = $this->get['uid'];
        $username = $this->get['username'];
        $password = $this->get['password'];
        $email = isset($this->get['email']) ? $this->get['email'] : '';
        $mobile = isset($this->get['mobile']) ? $this->get['mobile'] : '';
        if (!API_ADDUSER)
        {
            return API_RETURN_FORBIDDEN;
        }
        $time = time();
        $salt = Random::alnum();
        $password = $this->auth->getEncryptPassword($password, $salt);
        $ip = $this->request->ip();
        $userarr = [
            'id'       => $uid,
            'nickname' => $username,
            'username' => $username,
            'password' => $password,
            'level'    => 1,
            'score'    => 0,
            'avatar'   => '',
            'salt'     => $salt,
            'email'    => $email,
            'mobile'   => $mobile,
            'jointime' => $time,
            'joinip'   => $ip,
            'status'   => 'normal'
        ];
        User::create($userarr);
        return API_RETURN_SUCCEED;
    }

    /**
     * 更新用户信息,包含用户名,密码,邮箱,手机号和其它扩展信息
     */
    protected function updateinfo()
    {
        if (!API_UPDATEINFO)
        {
            return API_RETURN_FORBIDDEN;
        }
        $uid = isset($this->get['uid']) ? $this->get['uid'] : 0;
        $username = isset($this->get['username']) ? $this->get['username'] : '';
        $password = isset($this->get['password']) ? $this->get['password'] : '';
        $email = isset($this->get['email']) ? $this->get['email'] : '';
        $mobile = isset($this->get['mobile']) ? $this->get['mobile'] : '';
        $user = User::get($uid);
        if (!$user)
        {
            return API_RETURN_FAILED;
        }
        $values = [];
        if ($username)
        {
            $values['username'] = $username;
        }
        if ($password)
        {
            $salt = Random::alnum();
            $password = $this->auth->getEncryptPassword($password, $salt);
            $values['password'] = $password;
            $values['salt'] = $salt;
        }
        if ($email)
        {
            $values['email'] = $email;
        }
        if ($mobile)
        {
            $values['mobile'] = $mobile;
        }
        $user->save($values);
        return API_RETURN_SUCCEED;
    }

    /**
     * 更新禁言文字
     */
    protected function updatebadwords()
    {
        if (!API_UPDATEBADWORDS)
        {
            return API_RETURN_FORBIDDEN;
        }
        $cachefile = $this->appdir . './uc_client/data/cache/badwords.php';
        $fp = fopen($cachefile, 'w');
        $data = array();
        if (is_array($this->post))
        {
            foreach ($this->post as $k => $v)
            {
                $data['findpattern'][$k] = $v['findpattern'];
                $data['replace'][$k] = $v['replacement'];
            }
        }
        $s = "<?php\r\n";
        $s .= '$_CACHE[\'badwords\'] = ' . var_export($data, TRUE) . ";\r\n";
        fwrite($fp, $s);
        fclose($fp);
        return API_RETURN_SUCCEED;
    }

    /**
     * 更新HOSTS
     */
    protected function updatehosts()
    {
        if (!API_UPDATEHOSTS)
        {
            return API_RETURN_FORBIDDEN;
        }
        $cachefile = $this->appdir . './uc_client/data/cache/hosts.php';
        $fp = fopen($cachefile, 'w');
        $s = "<?php\r\n";
        $s .= '$_CACHE[\'hosts\'] = ' . var_export($this->post, TRUE) . ";\r\n";
        fwrite($fp, $s);
        fclose($fp);
        return API_RETURN_SUCCEED;
    }

    /**
     * 更新App信息
     */
    protected function updateapps()
    {
        if (!API_UPDATEAPPS)
        {
            return API_RETURN_FORBIDDEN;
        }
        $UC_API = $this->post['UC_API'];

        //note 写 app 缓存文件
        $cachefile = $this->appdir . './uc_client/data/cache/apps.php';
        $fp = fopen($cachefile, 'w');
        $s = "<?php\r\n";
        $s .= '$_CACHE[\'apps\'] = ' . var_export($this->post, TRUE) . ";\r\n";
        fwrite($fp, $s);
        fclose($fp);

        //note 写配置文件
        if (is_writeable($this->appdir . './config.inc.php'))
        {
            $configfile = trim(file_get_contents($this->appdir . './config.inc.php'));
            $configfile = substr($configfile, -2) == '?>' ? substr($configfile, 0, -2) : $configfile;
            $configfile = preg_replace("/define\('UC_API',\s*'.*?'\);/i", "define('UC_API', '$UC_API');", $configfile);
            if ($fp = @fopen($this->appdir . './config.inc.php', 'w'))
            {
                @fwrite($fp, trim($configfile));
                @fclose($fp);
            }
        }

        return API_RETURN_SUCCEED;
    }

    /**
     * 更新客户端配置文件
     */
    protected function updateclient()
    {
        if (!API_UPDATECLIENT)
        {
            return API_RETURN_FORBIDDEN;
        }
        $cachefile = $this->appdir . './uc_client/data/cache/settings.php';
        $fp = fopen($cachefile, 'w');
        $s = "<?php\r\n";
        $s .= '$_CACHE[\'settings\'] = ' . var_export($this->post, TRUE) . ";\r\n";
        fwrite($fp, $s);
        fclose($fp);
        return API_RETURN_SUCCEED;
    }

    /**
     * 更新积分
     */
    protected function updatecredit()
    {
        if (!API_UPDATECREDIT)
        {
            return API_RETURN_FORBIDDEN;
        }
        $credit = $this->get['credit'];
        $amount = $this->get['amount'];
        $uid = $this->get['uid'];
        return API_RETURN_SUCCEED;
    }

    /**
     * 获取积分
     */
    protected function getcredit()
    {
        if (!API_GETCREDIT)
        {
            return API_RETURN_FORBIDDEN;
        }
    }

    /**
     * 获取积分配置
     */
    protected function getcreditsettings()
    {
        if (!API_GETCREDITSETTINGS)
        {
            return API_RETURN_FORBIDDEN;
        }
        $credits = [
            '1' => array('威望', ''),
            '2' => array('金钱', '枚'),
        ];
        return $this->_serialize($credits);
    }

    /**
     * 更新积分配置
     */
    protected function updatecreditsettings()
    {
        if (!API_UPDATECREDITSETTINGS)
        {
            return API_RETURN_FORBIDDEN;
        }
        return API_RETURN_SUCCEED;
    }

}
