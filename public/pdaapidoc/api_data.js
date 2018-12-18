define({ "api": [
  {
    "type": "get",
    "url": "/api/stockCountList",
    "title": "盘点单列表",
    "version": "1.0.0",
    "name": "stockCountList",
    "group": "group_pda",
    "permission": [
      {
        "name": "所有用户"
      }
    ],
    "description": "<p>盘点单管理api</p>",
    "sampleRequest": [
      {
        "url": "/api/stockCountList"
      }
    ],
    "success": {
      "fields": {
        "返回值": [
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "msg",
            "description": "<p>1 为失败  0为成功</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "current_page",
            "description": "<p>当前页</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "prev_page_url",
            "description": "<p>上一页地址</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "next_page_url",
            "description": "<p>下一页地址</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "total",
            "description": "<p>总页数</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "id",
            "description": "<p>盘点单id</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "stock_count_sn",
            "description": "<p>盘点单编号</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "stock_name",
            "description": "<p>盘点仓库</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "title",
            "description": "<p>标题</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "operate",
            "description": "<p>操作人</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "status",
            "description": "<p>状态</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "created_at",
            "description": "<p>盘点日期</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "成功示例:",
          "content": "{\"code\":0,\"msg\":\"ok\",\"data\":{\"current_page\":1,\"prev_page_url\":null,\"next_page_url\":\"http:\\/\\/myscan.dev.com\\/api\\/pda\\/choiceStockCount?page=2\",\"total\":5,\"data\":[{\"id\":1,\"stock_count_sn\":\"123\",\"stock_name\":\"1\",\"title\":\"\\u7b2c\\u4e00\\u6b21\\u76d8\\u70b9\",\"operate\":\"\\u9648\\u7fd4\\u5b87\",\"status\":\"\\u672a\\u542f\\u52a8\",\"created_at\":{\"date\":\"2018-12-03 17:47:27.000000\",\"timezone_type\":3,\"timezone\":\"PRC\"}}]}}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "(json) 错误示例:",
          "content": "{\"code\":\"1\",\"api_msg\":\"失败\",\"data\":[]}",
          "type": "json"
        }
      ]
    },
    "filename": "app/Http/Controllers/Api/PDA/PDAController.php",
    "groupTitle": "pda模块"
  },
  {
    "type": "post",
    "url": "/api/pda/submitScan",
    "title": "盘点数据提交",
    "version": "1.0.0",
    "name": "submitScan",
    "group": "group_pda",
    "permission": [
      {
        "name": "所有用户"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "type",
            "description": "<p>类型 1代表盘点 2代表出库</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "stock_count_id",
            "description": "<p>若为类型1  必须提供盘点单的id</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "container",
            "description": "<p>编码  编码有两种</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "element.0",
            "description": "<p>扫描的产品编码</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "element.1",
            "description": "<p>数量</p>"
          },
          {
            "group": "Parameter",
            "type": "json",
            "optional": false,
            "field": "json",
            "description": "<p>格式示例(不能用可以联系我) [{&quot;container&quot;:&quot;daddafffdsfsd&quot;,&quot;element&quot;:[[&quot;07M10008A180&quot;,&quot;15&quot;],[&quot;07M10008A180&quot;,&quot;20&quot;]]},{&quot;container&quot;:&quot;daddafffdsfsd&quot;,&quot;element&quot;:[[&quot;07M10008A180&quot;,&quot;15&quot;],[&quot;07M10008A180&quot;,&quot;20&quot;]]}]</p>"
          }
        ]
      }
    },
    "description": "<p>提交扫描数据api</p>",
    "sampleRequest": [
      {
        "url": "/api/pda/submitScan"
      }
    ],
    "success": {
      "fields": {
        "返回值": [
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "container",
            "description": "<p>库位或者箱子编码</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "element.0",
            "description": "<p>产品编码+尺码</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "element.1",
            "description": "<p>产品数量</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "成功示例:",
          "content": "{\"code\":0,\"msg\":\"ok\",\"data\":[{\"container\":\"daddafffdsfsd\",\"element\":[[\"07M10008A180\",\"15\"],[\"07M10008A180\",\"20\"]]},{\"container\":\"daddafffdsfsd\",\"element\":[[\"07M10008A180\",\"15\"],[\"07M10008A180\",\"20\"]]}]}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "(json) 错误示例:",
          "content": "{\"code\":\"1\",\"msg\":\"失败\",\"data\":[]}",
          "type": "json"
        }
      ]
    },
    "filename": "app/Http/Controllers/Api/PDA/PDAController.php",
    "groupTitle": "pda模块"
  }
] });
