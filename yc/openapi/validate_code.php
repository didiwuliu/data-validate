<?php
/**
 * @title validate_code
 * @description
 * validate_code
 * @author zhangchunsheng423@gmail.org
 * @version V1.0
 * @date 2014-07-08
 * @copyright  Copyright (c) 2014-2014 Luomor Inc. (http://www.luomor.com)
 */
require("./functions.php");

$method = $_GET["method"] ? $_GET["method"] : "get";
$cellphone = $_GET["cellphone"] ? $_GET["cellphone"] : "16811116667";
$server = isset($_GET["server"]) ? $_GET["server"] : "test";// release test

if($method == "get") {
    $result = json_decode(get($cellphone, $server));
    echo json_encode($result);
} else {
    echo '{"ret_code":201}';
}

function get($cellphone, $server = "test") {
    $results = grabValidateCode($server);
    $data = array(
        "ret_code" => 201,
        "validate_code" => 0
    );
    foreach($results as $result) {
        if($result["cellphone"] == $cellphone) {
            $data["ret_code"] = 200;
            $data["validate_code"] = $result["code"];
            break;
        }
    }
    return json_encode($data);
}

function grabValidateCode($server = "test") {
    if($server == "release") {
        $url = "http://platform.yongche.com/validatecode/";
        $array = array(

        );

        $header = array(
            'User-Agent:Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/35.0.1916.153 Safari/537.36',
            'Accept-Encoding:deflate',
            'Cookie:B=111.207.160.67.1398416337077892; A=a=1&c=&g=&i=1771597&s=&sf=&t=1398855415&v=2&sign=10e6023e971289bafaf0f40be6ed7961e1532cd5bf17fd1519af13baec6fcc62; _jzqx=1.1398878110.1401509418.2.jzqsr=yongche%2Ecom|jzqct=/user/discount%2Ephp.jzqsr=cms%2Eyongche%2Ecom|jzqct=/cms/edit; CRM=NzIz%7C1403097557%7Cc4138262b50ce87df9d47dc648ac47ee; Hm_lvt_6e2f71e81445b2f100f508d759d5d381=1404124909; Hm_lpvt_6e2f71e81445b2f100f508d759d5d381=1404124909; pgv_pvi=7930105856; pgv_si=s5036176384; _ga=GA1.2.1705812798.1398416337; CE=1; U=93c255f40d9926a6547d136bb5303721fc8e9e3b109df4a0; _jzqa=1.2318493447757372400.1398416339.1404386772.1404731258.34; _jzqc=1; _jzqckmp=1; __utma=264361863.1705812798.1398416337.1404439287.1404730786.37; __utmc=264361863; __utmz=264361863.1404730786.37.8.utmcsr=cms.yongche.com|utmccn=(referral)|utmcmd=referral|utmcct=/cms/list; Hm_lvt_24b50d3a9a73a0efd25feab8f04d313f=1404381727; Hm_lpvt_24b50d3a9a73a0efd25feab8f04d313f=1404734088; I=0a0105d4'
        );
    } elseif($server == "test") {
        $url = "http://zhangchunsheng.be.yongche.org/validatecode/";
        $array = array(

        );

        $header = array(
            'User-Agent:Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/35.0.1916.153 Safari/537.36',
            'Accept-Encoding:deflate',
            'Cookie:B=10.0.11.235.1399609244299678; _jzqa=1.2758095973207149600.1399609246.1399609246.1399609246.1; _jzqc=1; AGENT_OPERATOR=MQ%3D%3D%7C1400048270%7C960ea603bcdb1d809025fdb4b81c4600; CRM=NDk5%7C1403767600%7C24743325523fba13eeabe18841745788; I=0a0105d4'
        );
    }

    $result = request($url, "GET", $array, $header);
    $regex = "/<tbody>(.*?)<\/tbody>/s";
    $matches = array();

    $data = array();
    if(preg_match($regex, $result, $matches)) {
        if(count($matches) > 1) {
            $tr = $matches[1];
            $regex = "/<tr>(.*?)<\/tr>/s";

            $matches = array();
            if(preg_match_all($regex, $tr, $matches)) {
                if(count($matches) > 1) {
                    $tds = $matches[1];
                    foreach($tds as $td) {
                        $regex = "/<td>(.*?)<\/td>/s";

                        $matches = array();
                        if(preg_match_all($regex, $td, $matches)) {
                            if(count($matches) > 1) {
                                $data[] = array(
                                    "cellphone" => $matches[1][0],
                                    "code" => $matches[1][1]
                                );
                            }
                        }
                    }
                }
            }
        }
    }

    return $data;
}

/**
 * 正则表达式的匹配先后顺序:
1.模式单元
2.重复匹配 ? * + {}
3.边界限定 ^ $ b B
4.模式选择 |

模式修正符:
模式修正符是标记在整个模式之外的.

i :模式中的字符将同时匹配大小写字母.
m :字符串视为多行.
s :将字符串视为单行,换行符作为普通字符.
x :将模式中的空白忽略.
A :强制仅从目标字符串的开头开始匹配.
D :模式中的美元元字符仅匹配目标字符串的结尾.
U :匹配最近的字符串.


PHP与正则表达式中的模式修正符

Submitted by 孤魂 on 2009, July 23, 8:56 PM. 学习┊取长补短
下面列出了当前在 PCRE 中可能使用的修正符。括号中是这些修正符的内部 PCRE 名。

i (PCRE_CASELESS)
如果设定此修正符，模式中的字符将同时匹配大小写字母。

m (PCRE_MULTILINE)
默认情况下，PCRE 将目标字符串作为单一的一“行”字符所组成的（甚至其中包含有换行符也是如此）。“行起始”元字符（^）仅仅匹配字符串的 起始，“行结束”元字符（$）仅仅匹配字符串的结束，或者最后一个字符是换行符时其前面（除非设定了 D 修正符）。这和 Perl 是一样的。

当设定了此修正符，“行起始”和“行结束”除了匹配整个字符串开头和结束外，还分别匹配其中的换行符的之后和之前。这和 Perl 的 /m 修正符是等效的。如果目标字符串中没有“\n”字符或者模式中没有 ^ 或 $，则设定此修正符没有任何效果。

s (PCRE_DOTALL)
如果设定了此修正符，模式中的圆点元字符（.）匹配所有的字符，包括换行符。没有此设定的话，则不包括换行符。这和 Perl 的 /s 修正符是等效的。排除字符类例如 [^a] 总是匹配换行符的，无论是否设定了此修正符。

x (PCRE_EXTENDED)
如果设定了此修正符，模式中的空白字符除了被转义的或在字符类中的以外完全被忽略，在未转义的字符类之外的 # 以及下一个换行符之间的所有字符，包括两头，也都被忽略。这和 Perl 的 /x 修正符是等效的，使得可以在复杂的模式中加入注释。然而注意，这仅适用于数据字符。空白字符可能永远不会出现于模式中的特殊字符序列，例如引入条件子模式的序列 (?( 中间。

e
如果设定了此修正符，preg_replace() 在替换字符串中对逆向引用作正常的替换，将其作为 PHP 代码求值，并用其结果来替换所搜索的字符串。

只有 preg_replace() 使用此修正符，其它 PCRE 函数将忽略之。

注: 本修正符在 PHP3 中不可用。

A (PCRE_ANCHORED)
如果设定了此修正符，模式被强制为“anchored”，即强制仅从目标字符串的开头开始匹配。此效果也可以通过适当的模式本身来实现（在 Perl 中实现的唯一方法）。

D (PCRE_DOLLAR_ENDONLY)
如果设定了此修正符，模式中的美元元字符仅匹配目标字符串的结尾。没有此选项时，如果最后一个字符是换行符的话，美元符号也会匹配此字符之前（但不会匹配任何其它换行符之前）。如果设定了 m 修正符则忽略此选项。Perl 中没有与其等价的修正符。

S
当一个模式将被使用若干次时，为加速匹配起见值得先对其进行分析。如果设定了此修正符则会进行额外的分析。目前，分析一个模式仅对没有单一固定起始字符的 non-anchored 模式有用。

U (PCRE_UNGREEDY)
本修正符反转了匹配数量的值使其不是默认的重复，而变成在后面跟上“?”才变得重复。这和 Perl 不兼容。也可以通过在模式之中设定 (?U) 修正符来启用此选项。

X (PCRE_EXTRA)
此修正符启用了一个 PCRE 中与 Perl 不兼容的额外功能。模式中的任何反斜线后面跟上一个没有特殊意义的字母导致一个错误，从而保留此组合以备将来扩充。默认情况下，和 Perl 一样，一个反斜线后面跟一个没有特殊意义的字母被当成该字母本身。当前没有其它特性受此修正符控制。

u (PCRE_UTF8)
此修正符启用了一个 PCRE 中与 Perl 不兼容的额外功能。模式字符串被当成 UTF-8。本修正符在 Unix 下自 PHP 4.1.0 起可用，在 win32 下自 PHP 4.2.3 起可用。

举例：
//标记在整个模式之外;
例://$mode="/\bis\b/U",其中U在外面;
//修正符:i 不区分大小写的匹配;

//如:"/abc/i"可以与abc或aBC或ABc等匹配;
//修正符:m 将字符串视为多行,不管是那行都能匹配;

例://模式为:$mode="/abc/m";
//要匹配的字符串为:$str="bcefg5e\nabcdfe"
//注意其中\n,换行了;abc换到了下一行;
//$str和$mode仍可以匹配,修正符m使得多行也可匹配;
//修正符:s 将字符串视为单行,换行符作为普通字符;

例://模式为:$mode="/pr.y/";
//要匹配字符串为:$str="pr\ny";
//两者不可匹配; . 是除了换行以外的字符可匹配;
//修改下模式为:$mode="/pr.y/s";
//其中修正符s将\n视为普通字符,即不是换行;
//最后两者可以匹配;
//修正符:x 将模式中的空白忽略;
//修正符:A 强制从目标字符串开头匹配;

例://$mode="/abc/A";
//可以与$str="abcsdfi"匹配,
//不可以与$str2="sdsdabc"匹配;
//因为$str2不是以abc开头;
//修正符:D 如果使用$限制结尾字符,则不允许结尾有换行;

例://模式为:$mode="/abc$/";
//可以与最后有换行的$str="adshabc\n"匹配;
//元子符$会忽略最后的换行\n;
//如果模式为:$mode="/abc/D",
//则不能与$str="adshabc\n"匹配,
//修正符D限制其不可有换行;必需以abc结尾;
//修正符:U 只匹配最近的一个字符串;不重复匹配;

例:
如模式为:
$mode="/a.*c/";
$str="abcabbbcabbbbbc" ;
preg_match($mode,$str,$content);
echo $content[0]; //输出:abcabbbcabbbbbc;

//如果$mode="/a.*c/";变成$mode="/a.*c/U";
// 则只匹配最近一个字符串,输出:abc;

//修正符:e 配合函数preg_replace()使用,
可以把匹配来的字符串当作正则表达式执行;
 *
 * <?php
$s = <<<str
<dl class="huankuan" style=" margin-top:15px;">
<dd>AAAA</dd>
<dd>BBBB</dd>
<dd></dd>
</dl>
str;
// 一次匹配，可以把 <dl><dd></dd> 看作一个整体
$p = '@(<dl.*>)?\s*<dd>(.*)</dd>\s*(</dl>)?@Us';
preg_match_all($p, $s, $r);
var_dump($r);
 *
 * preg_match("/<dl class=\"huankuan\" style=\" margin-top:15px;\">(.*?)<\/dl>/s",$html,$matches1);//提取dd部分
preg_match_all("/(<dd>(.*?)<\/dd>)/",$matches1[1],$matches2);
print_r($matches2);//提取dd里面的文字
 */