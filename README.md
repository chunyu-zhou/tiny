# tiny
基于phalapi修改来的框架版本

## 快速安装

### composer一键安装

使用composer创建项目的命令，可实现一键安装。

```bash
$ composer create-project chunyu/tiny
```
> 温馨提示：关于composer的使用，请参考[Composer 中文网 / Packagist 中国全量镜像](http://www.phpcomposer.com/)。

### 手动下载安装

或者，也可以进行手动安装。将此Git项目代码下载解压后，进行可选的composer更新，即：  
```bash
$ composer update
```

### 访问接口服务

随后，可通过以下链接，访问默认接口服务。  
```
http://localhost/path/to/tiny/public/
```
可以看到类似这样的输出：  
```
{
    "ret": 200,
    "data": {
        "title": "Hello Tiny",
        "version": "2.0.1",
        "time": 1501079142
    },
    "msg": ""
}
```


> 温馨提示：推荐将访问根路径指向/path/to/tiny/public。

更多请见：[Tiny 1.x 开发文档](http://tiny.zcyso.cn/)  

## 发现问题，怎么办？  

如发现问题，或者任何问题，欢迎提交Issue到[这里](https://github.com/chunyu-zhou/tiny/issues)。
