define({ "api": [
  {
    "type": "post",
    "url": "/api/pda/login",
    "title": "pda登陆接口",
    "version": "2.0.0",
    "name": "login",
    "group": "group_pda_login",
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
            "type": "int",
            "optional": false,
            "field": "user_name",
            "description": "<p>用户名称</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "password",
            "description": "<p>用户密码</p>"
          }
        ]
      }
    },
    "description": "<p>pda登陆接口api</p>",
    "sampleRequest": [
      {
        "url": "/api/pda/login"
      }
    ],
    "success": {
      "fields": {
        "返回值": [
          {
            "group": "返回值",
            "type": "int",
            "optional": false,
            "field": "code",
            "description": "<p>状态码</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "msg",
            "description": "<p>提示信息</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "token_type",
            "description": "<p>令牌类型</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "expires_in",
            "description": "<p>过期事时间</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "access_token",
            "description": "<p>令牌</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "refresh_token",
            "description": "<p>刷新令牌</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "成功示例:",
          "content": "{\"code\":0,\"msg\":\"ok\",\"data\":{\"token_type\":\"Bearer\",\"expires_in\":31536000,\"access_token\":\"eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImp0aSI6IjIwMzQ4Y2M0MzQ3MWU0ZGRjNTY3NWQxNjM3MDc3MmFmZTk5ZmUzOWJjM2U0ODYwZGU3YjQ4NDA2YzE0MGQ0ZmY0YjJjMjM1MzNhNjA4MzkxIn0.eyJhdWQiOiIyIiwianRpIjoiMjAzNDhjYzQzNDcxZTRkZGM1Njc1ZDE2MzcwNzcyYWZlOTlmZTM5YmMzZTQ4NjBkZTdiNDg0MDZjMTQwZDRmZjRiMmMyMzUzM2E2MDgzOTEiLCJpYXQiOjE1NDY4NDE0NzgsIm5iZiI6MTU0Njg0MTQ3OCwiZXhwIjoxNTc4Mzc3NDc4LCJzdWIiOiIxIiwic2NvcGVzIjpbIioiXX0.PYoqwuH1-jC-T6cfjO8zQJtmt3olJ5R-xqEcxXb1TCcua-daFvAJKzMYHS2-oLKcheaEcPUNZVjQjpBRva6aQelH74ML_xNjvfbtpJ7hcDx6PO2wAZ_pwTM7LF9ODwxTqhw5GLBpv_zdXekpCrpNTX5GhesAIYIfDGWDag_bO2KtEmln7F3GuEc5qW1KCQfGN0ZqxGJNhSGy_sxQAf45b14gB17BPr89UjYfFVXsE2feHjVRBpa84oGHtNMB3wRTU58MNUzk0i4ur9zaHfOkFQqq0bcPZVRqQucicrgwDF3Th418TC-pKw3q_fOianLevvC7LwNWP7gm844fC-A8yoeAB-SbekudHMK6kNS8SGrzScRyXYLLBtijH8Ky5P0m3y5JQuIA1RVxLTOnguLGHvRiLeHgziU0XccA9n2TKhr1g71jlb3xdP5GSW8gyoZ4jLNq9du0gd6y1TtL2cpSWFqHE4aCtG_wUbGSnr1_Fv_HrcSUPjMGTj3hhxP0UTGb1RV6vstkKmxNXG6r52XXde8swugOePDTWv3LM2S4HBMXteNPYVvzmFb9RjyAjoMsvSO4H36Dyk8KUyoviB4mcgShrrkFZcPU4WIc_1XEdVp1uMqqjwjX-GVQcMExmeimWkJmG6w0UnVEe9ypEcH_7pz-VnOH0gNcCdnezNOfUVQ\",\"refresh_token\":\"def50200c677edd71cbfbeefb2577fcf3aeded75295d4f9ce2964647b34b369dabb46189551f19bd65978ffdad05ace040a20dfbc3d0058dda9a61f1cd5d61d67192d4a9fc6e9a8c20a616da87be98115f26e2a3ab7365849540c4c85efb976e61ef1873b63388b9a66ffa367966e4074287ef7151ea715f44d8ae742ac6126d5d62f51c06b74a000df98529797b2511d40cd611e7f3eee7e52d928e0e5deb09b470b9d9142463fa06a93adbc4b6124614ab0194242e8296797024098353347207291003deb61d7ebceb9f8ebf6e5cceb4407426bbeb2fa0d73b89dd4d3a2d2e673f607b69d97c8446dab6be00b8953452740b1fb5c17ef87e5024019e0fb6ce7020e1a4cca14ef1d89017194456eee1a1d135d0acb96a7b47cea912e2a2e69dc66378394500d03282bd7b806d34d7f96bbaa9df0e7b1503c45a5375e22e8834eb0ac71d5ffb5cff423a8ed0b016a4d6fa74055cee4fc31015bbb1bfee87e89fe4a4\"}}",
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
    "filename": "app/Http/Controllers/Api/PDA/LoginController.php",
    "groupTitle": "pda登陆模块"
  },
  {
    "type": "get",
    "url": "/api/pda/logout",
    "title": "pda登出操作",
    "version": "2.0.0",
    "name": "logout",
    "group": "group_pda_login",
    "permission": [
      {
        "name": "登录用户"
      }
    ],
    "description": "<p>pda登出操作api</p>",
    "sampleRequest": [
      {
        "url": "/api/pda/logout"
      }
    ],
    "success": {
      "fields": {
        "返回值": [
          {
            "group": "返回值",
            "type": "int",
            "optional": false,
            "field": "code",
            "description": "<p>状态码</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "msg",
            "description": "<p>提示信息</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "成功示例:",
          "content": "{\"code\":0,\"msg\":\"\\u767b\\u51fa\\u6210\\u529f\",\"data\":null}",
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
    "filename": "app/Http/Controllers/Api/PDA/LoginController.php",
    "groupTitle": "pda登陆模块"
  },
  {
    "type": "get",
    "url": "/api/pda/refresh",
    "title": "刷新一个令牌",
    "version": "2.0.0",
    "name": "refresh",
    "group": "group_pda_login",
    "permission": [
      {
        "name": "登录用户"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "address_id",
            "description": "<p>收货地址信息</p>"
          }
        ]
      }
    },
    "description": "<p>刷新一个令牌api</p>",
    "sampleRequest": [
      {
        "url": "/api/pda/refresh"
      }
    ],
    "success": {
      "fields": {
        "返回值": [
          {
            "group": "返回值",
            "type": "int",
            "optional": false,
            "field": "code",
            "description": "<p>状态码</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "msg",
            "description": "<p>提示信息</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "token_type",
            "description": "<p>令牌类型</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "expires_in",
            "description": "<p>过期事时间</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "access_token",
            "description": "<p>令牌</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "refresh_token",
            "description": "<p>刷新令牌</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "成功示例:",
          "content": "{\"code\":0,\"msg\":\"ok\",\"data\":{\"token_type\":\"Bearer\",\"expires_in\":31536000,\"access_token\":\"eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImp0aSI6IjYzYmU1MWUyY2UzNzNhNjE3NmNhNWM2ZGZkOGU0YmMxZDJlNDI1NzFiYjdlYTMwMGQzOGY0ZGM0ZGZmMzM0OGM4ZGUzNjE3NzVhNzNiMGQ4In0.eyJhdWQiOiIyIiwianRpIjoiNjNiZTUxZTJjZTM3M2E2MTc2Y2E1YzZkZmQ4ZTRiYzFkMmU0MjU3MWJiN2VhMzAwZDM4ZjRkYzRkZmYzMzQ4YzhkZTM2MTc3NWE3M2IwZDgiLCJpYXQiOjE1NDY4NDgwMDQsIm5iZiI6MTU0Njg0ODAwNCwiZXhwIjoxNTc4Mzg0MDA0LCJzdWIiOiIxIiwic2NvcGVzIjpbXX0.AW6utXKX7Sj4eAOIEJ609HVQGPdxeSUKix2gMuekCe8BTFD1RZcpV8w8UXz8MSk5PQuFww09LMxiMmEx-XNjeYXyy3wMIPJTM8jIK27lYO02MnnUpC5Lg3MEzcUsven7SOq5r37rz4AVNQ6fLdFprqt846X0A9UjgtoUoYgZjqKcqZ3QhekiAppaUcTNMJncOhO08JKqspzpJ98ua_049ePpjnGf_pQcr4NGzuXHBeWJZ7wEJe393GEPhZ8uhJacUhmqSwB_hAwryYpM2X5Kmj-QiwWqVZtxk9CJKYaeZQc5ApCofsUY8ZAiAy2xdvGREr-fXWVT_Z5AmI7olvpPaXP0yC9hQ7uNF_fsTx5ucJJoFrSfniZuxTVKlWc_L1Rs_SOIQHVo2hRmJA90bzOZSYAiO2TKOmPrcn-2dqYifceru6jkQRqidH-U_u5lkLeCA_8fY7a2PsKKI2fWqy5Nr70GbvABVRd-Af-rN0I5n-FT_eJNOuhBw70CXMX3wukbFBWtrTdDi1mXL0ICGfqD2_wvzx28sX1A2o0gruz68fvxqTywjHaxpaQ9iBkplGl-3Zx9K54aZ5JE2y5Kut2LkdMpOSKE8tWbE1lwxOy79noR19xn0bbXti7ZwyJtHnFcoH_PD2DcAYmS7-2JW8MCnBrKOt0uw58TU_hgrOVn3e4\",\"refresh_token\":\"def502009e25960be4ad4175daf11c085f4c3a798670412b06a861dd26d871b8697c74232dfd0e53e7c951dcac27643c1da2d62d2c057fff31a7815d19002d31f9914edfcd9c996fbf69e5ef09ca6820b9eff1bef37a47862ab425fa93a6e4549e5650844dc61917dbb56e260c4d7ca411b29d925e8f6ef1124c55a1242f30ef8accdcbbe721dbf441b4f2e3e9996a697a22e3b98aabdf918b301a6250f8bf31ae8b14f7aba4c05f2a20b31471670f34d166e86ccaac8b63d8d626a8c2cfde4a66c545478912287df7114eac10258cc73527ac9428c949e827cc8260fe5074ef8e9e5c17794b8c93b87d59c6bf531fd40c44d48c777f8cb81b4c291ad2e50ba26cf476b95a43c1ff655e857a41e614332a2a7c3048a8edd199484cacadb9fbaa61054ddf1234855b6bb9139ba9fcd2c8c8a2da5e43cba3d8339e90f229bdc02ea81dfac6ab7faa4256c11de0a6742ae570d8ba6eb1e7df85561fa1f35b797f\"}}",
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
    "filename": "app/Http/Controllers/Api/PDA/LoginController.php",
    "groupTitle": "pda登陆模块"
  },
  {
    "type": "post",
    "url": "/api/pda/addMoveList",
    "title": "新增一个移位单",
    "version": "1.0.0",
    "name": "addMoveList",
    "group": "group_pda",
    "permission": [
      {
        "name": "登录用户"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "name",
            "description": "<p>移库单名称</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "operate",
            "description": "<p>操作者</p>"
          }
        ]
      }
    },
    "description": "<p>新增一个移位单api</p>",
    "sampleRequest": [
      {
        "url": "/api/pda/addMoveList"
      }
    ],
    "success": {
      "fields": {
        "返回值": [
          {
            "group": "返回值",
            "type": "int",
            "optional": false,
            "field": "code",
            "description": "<p>状态码</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "msg",
            "description": "<p>提示信息</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "stock_move_sn",
            "description": "<p>移库单编码</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "成功示例:",
          "content": "{\"code\":0,\"msg\":\"ok\",\"data\":{\"stock_move_sn\":\"CKXH_812289859\"}}",
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
  },
  {
    "type": "post",
    "url": "/api/pda/addStockIn",
    "title": "新增一个入库单",
    "version": "1.0.0",
    "name": "addStockIn",
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
            "field": "name",
            "description": "<p>入库名称</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "j_h_time",
            "description": "<p>交货时间</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "type",
            "description": "<p>入库类型</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "operate",
            "description": "<p>操作者</p>"
          }
        ]
      }
    },
    "description": "<p>新增一个入库单api</p>",
    "sampleRequest": [
      {
        "url": "/api/pda/addStockIn"
      }
    ],
    "success": {
      "fields": {
        "返回值": [
          {
            "group": "返回值",
            "type": "int",
            "optional": false,
            "field": "id",
            "description": "<p>入库单id</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "name",
            "description": "<p>入库名称</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "stock_in_sn",
            "description": "<p>入库单编码</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "j_h_time",
            "description": "<p>交货时间</p>"
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
            "field": "type",
            "description": "<p>入库类型</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "created_at",
            "description": "<p>日期</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "created_at.date",
            "description": "<p>提示信息创建日期</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "成功示例:",
          "content": "{\"code\":1,\"msg\":\"所选地址不存在\",\"data\":[]}",
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
  },
  {
    "type": "post",
    "url": "/api/pda/addStockOut",
    "title": "新增一个出库单",
    "version": "2.0.0",
    "name": "addStockOut",
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
            "field": "name",
            "description": "<p>出库名称</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "type",
            "description": "<p>出库类型</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "operate",
            "description": "<p>操作者</p>"
          }
        ]
      }
    },
    "description": "<p>新增一个出库单api</p>",
    "sampleRequest": [
      {
        "url": "/api/pda/addStockOut"
      }
    ],
    "success": {
      "fields": {
        "返回值": [
          {
            "group": "返回值",
            "type": "int",
            "optional": false,
            "field": "id",
            "description": "<p>出库单id</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "name",
            "description": "<p>出库名称</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "stock_out_sn",
            "description": "<p>入库单编码</p>"
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
            "field": "type",
            "description": "<p>出库类型</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "created_at",
            "description": "<p>日期</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "created_at.date",
            "description": "<p>提示信息创建日期</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "成功示例:",
          "content": "{\"code\":0,\"msg\":\"ok\",\"data\":{\"stock_out_sn\":\"CKOUT_901034773\",\"name\":\"123\",\"type\":\"789\",\"operate\":\"456\",\"created_at\":\"2019-01-03 14:09:46\",\"id\":5}}",
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
  },
  {
    "type": "get",
    "url": "/api/pda/applyMoveStock",
    "title": "移位操作",
    "version": "2.0.0",
    "name": "applyMoveStock",
    "group": "group_pda",
    "permission": [
      {
        "name": "登录用户"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "or_stock_sn",
            "description": "<p>源库位编码</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "ta_stock_sn",
            "description": "<p>目标库位编码</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "data",
            "description": "<p>[{&quot;sn&quot;:&quot;CKXH201896c728&quot;,&quot;num&quot;:1},{&quot;sn&quot;:&quot;T1805136A130&quot;,&quot;num&quot;:1}]</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "stock_move_sn",
            "description": "<p>移位单号</p>"
          }
        ]
      }
    },
    "description": "<p>移位操作api</p>",
    "sampleRequest": [
      {
        "url": "/api/pda/applyMoveStock"
      }
    ],
    "success": {
      "fields": {
        "返回值": [
          {
            "group": "返回值",
            "type": "int",
            "optional": false,
            "field": "code",
            "description": "<p>状态码</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "msg",
            "description": "<p>提示信息</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "成功示例:",
          "content": "{\"code\":0,\"msg\":\"ok\",\"data\":[]}",
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
  },
  {
    "type": "get",
    "url": "/api/pda/listNotBindingBox",
    "title": "列出入库单所有已检针未绑定的箱子号",
    "version": "1.0.0",
    "name": "listNotBindingBox",
    "group": "group_pda",
    "permission": [
      {
        "name": "登录用户"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "stock_in_sn",
            "description": "<p>入库单id</p>"
          }
        ]
      }
    },
    "description": "<p>列出入库单所有已检针未绑定的箱子号api</p>",
    "sampleRequest": [
      {
        "url": "/api/pda/listNotBindingBox"
      }
    ],
    "success": {
      "fields": {
        "返回值": [
          {
            "group": "返回值",
            "type": "int",
            "optional": false,
            "field": "code",
            "description": "<p>状态码</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "msg",
            "description": "<p>提示信息</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "成功示例:",
          "content": "{\"code\":0,\"msg\":\"\\u6210\\u529f\",\"data\":{\"1\":\"CKXH1812130002\"}}",
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
  },
  {
    "type": "get",
    "url": "/api/pda/queryStockHas",
    "title": "库位中所有箱子和产品",
    "version": "2.0.0",
    "name": "queryStockHas",
    "group": "group_pda",
    "permission": [
      {
        "name": "登录用户"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "stock_sn",
            "description": "<p>库位编码</p>"
          }
        ]
      }
    },
    "description": "<p>库位中所有箱子和产品api</p>",
    "sampleRequest": [
      {
        "url": "/api/pda/queryStockHas"
      }
    ],
    "success": {
      "fields": {
        "返回值": [
          {
            "group": "返回值",
            "type": "int",
            "optional": false,
            "field": "box_sn",
            "description": "<p>箱子编码 或者产品编码</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "fashion_num",
            "description": "<p>产品数量</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "成功示例:",
          "content": "{\"code\":0,\"msg\":\"ok\",\"data\":[{\"box_sn\":\"CKXH1812130001\",\"fashion_num\":6},{\"fashion_num\":5,\"box_sn\":\"07M10008A180\"},{\"fashion_num\":5,\"box_sn\":\"07M10008A180\"}]}",
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
  },
  {
    "type": "get",
    "url": "/api/pda/setEndCheckNeedle",
    "title": "设置针检完成",
    "version": "1.0.0",
    "name": "setEndCheckNeedle",
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
            "type": "int",
            "optional": false,
            "field": "stock_in_id",
            "description": "<p>入库单id</p>"
          }
        ]
      }
    },
    "description": "<p>设置针检完成api</p>",
    "sampleRequest": [
      {
        "url": "/api/pda/setEndCheckNeedle"
      }
    ],
    "success": {
      "fields": {
        "返回值": [
          {
            "group": "返回值",
            "type": "int",
            "optional": false,
            "field": "id",
            "description": "<p>入库单id</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "stock_in_sn",
            "description": "<p>编号</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "name",
            "description": "<p>入库名称</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "box_num",
            "description": "<p>箱子数量</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "fashion_num",
            "description": "<p>商品数量</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "成功示例:",
          "content": "{\"code\":0,\"msg\":\"ok\",\"data\":{\"id\":1,\"stock_in_sn\":\"123\",\"name\":\"\\u7b2c\\u4e00\\u6b21\\u5165\\u5e93\",\"box_num\":2,\"fashion_num\":4}}",
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
  },
  {
    "type": "get",
    "url": "/api/pda/stockCountList",
    "title": "盘点单列表",
    "version": "1.0.0",
    "name": "stockCountList",
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
            "type": "int",
            "optional": false,
            "field": "page",
            "description": ""
          }
        ]
      }
    },
    "description": "<p>盘点单管理api</p>",
    "sampleRequest": [
      {
        "url": "/api/pda/stockCountList"
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
    "type": "get",
    "url": "/api/pda/stockInList",
    "title": "入库单列表",
    "version": "2.0.0",
    "name": "stockInList",
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
            "type": "int",
            "optional": false,
            "field": "page",
            "description": "<p>页数</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "type",
            "description": "<p>入库类型</p>"
          }
        ]
      }
    },
    "description": "<p>入库单列表api</p>",
    "sampleRequest": [
      {
        "url": "/api/pda/stockInList"
      }
    ],
    "success": {
      "fields": {
        "返回值": [
          {
            "group": "返回值",
            "type": "int",
            "optional": false,
            "field": "id",
            "description": "<p>入库单id</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "stock_in_sn",
            "description": "<p>入库单编码</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "name",
            "description": "<p>入库单名称</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "type",
            "description": "<p>入库单类型</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "operate",
            "description": "<p>操作者</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "j_h_time",
            "description": "<p>交货时间</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "z_j_is_end",
            "description": "<p>检针是否结束</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "b_x_is_end",
            "description": "<p>搬箱是否结束</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "j_z_box_num",
            "description": "<p>检针箱数</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "r_k_box_num",
            "description": "<p>入库箱数</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "wait_ban_box_num",
            "description": "<p>待搬箱箱数</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "成功示例:",
          "content": "{\"code\":0,\"msg\":\"ok\",\"data\":{\"current_page\":1,\"prev_page_url\":null,\"next_page_url\":\"http:\\/\\/myscan.dev.com\\/api\\/pda\\/stockInList?page=2\",\"total\":11,\"data\":[{\"id\":1,\"stock_in_sn\":\"123\",\"name\":\"\\u7b2c\\u4e00\\u6b21\\u5165\\u5e93\",\"type\":\"\\u9000\\u8d27\\u5165\\u5e93\",\"operate\":\"\\u9648\\u7fd4\\u5b87\",\"j_h_time\":null,\"z_j_is_end\":\"\\u5b8c\\u6210\",\"b_x_is_end\":\"\\u5b8c\\u6210\",\"j_z_box_num\":8,\"r_k_box_num\":0,\"wait_ban_box_num\":8}]}}",
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
  },
  {
    "type": "post",
    "url": "/api/pda/stockOut",
    "title": "出库动作",
    "version": "2.0.0",
    "name": "stockOut",
    "group": "group_pda",
    "permission": [
      {
        "name": "登录用户"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "stock_out_id",
            "description": "<p>出库单id</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "data",
            "description": "<p>出库单数据</p>"
          }
        ]
      }
    },
    "description": "<p>出库动作api</p>",
    "sampleRequest": [
      {
        "url": "/api/pda/stockOut"
      }
    ],
    "success": {
      "fields": {
        "返回值": [
          {
            "group": "返回值",
            "type": "int",
            "optional": false,
            "field": "code",
            "description": "<p>状态码</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "msg",
            "description": "<p>提示信息</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "成功示例:",
          "content": "{\"code\":0,\"msg\":\"ok\",\"data\":[{\"container\":\"4_S_A01Z01C01\",\"element\":{\"fashion_info\":[{\"fashion_code\":\"T1806090A\",\"fashion_size\":\"120\",\"fashion_num\":1}],\"box_info\":[]}}]}",
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
  },
  {
    "type": "get",
    "url": "/api/pda/stockOutList",
    "title": "出库单列表",
    "version": "2.0.0",
    "name": "stockOutList",
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
            "type": "int",
            "optional": false,
            "field": "page",
            "description": "<p>页数</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "type",
            "description": "<p>出库类型</p>"
          }
        ]
      }
    },
    "description": "<p>出库单列表api</p>",
    "sampleRequest": [
      {
        "url": "/api/pda/stockOutList"
      }
    ],
    "success": {
      "fields": {
        "返回值": [
          {
            "group": "返回值",
            "type": "int",
            "optional": false,
            "field": "id",
            "description": "<p>出库单id</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "name",
            "description": "<p>出库名称</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "stock_out_sn",
            "description": "<p>入库单编码</p>"
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
            "field": "type",
            "description": "<p>出库类型</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "created_at",
            "description": "<p>日期</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "created_at.date",
            "description": "<p>提示信息创建日期</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "成功示例:",
          "content": "{\"code\":0,\"msg\":\"ok\",\"data\":{\"current_page\":1,\"prev_page_url\":null,\"next_page_url\":\"http:\\/\\/myscan.dev.com\\/api\\/pda\\/stockInList?page=2\",\"total\":11,\"data\":[{\"id\":1,\"stock_in_sn\":\"123\",\"name\":\"\\u7b2c\\u4e00\\u6b21\\u5165\\u5e93\",\"type\":\"\\u9000\\u8d27\\u5165\\u5e93\",\"operate\":\"\\u9648\\u7fd4\\u5b87\",\"j_h_time\":null,\"z_j_is_end\":\"\\u5b8c\\u6210\",\"b_x_is_end\":\"\\u5b8c\\u6210\",\"j_z_box_num\":8,\"r_k_box_num\":0,\"wait_ban_box_num\":8}]}}",
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
  },
  {
    "type": "post",
    "url": "/api/pda/submitAudit",
    "title": "提交审核",
    "version": "1.0.0",
    "name": "submitAudit",
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
            "type": "int",
            "optional": false,
            "field": "stock_in_id",
            "description": "<p>入库单id</p>"
          }
        ]
      }
    },
    "description": "<p>提交审核api</p>",
    "sampleRequest": [
      {
        "url": "/api/pda/submitAudit"
      }
    ],
    "success": {
      "fields": {
        "返回值": [
          {
            "group": "返回值",
            "type": "int",
            "optional": false,
            "field": "id",
            "description": "<p>状态码</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "stock_in_sn",
            "description": "<p>入库编码</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "stock_sn_s",
            "description": "<p>入库箱子</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "has_box_num",
            "description": "<p>入库箱子数量</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "detail",
            "description": "<p>详情</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "detail.stock_sn",
            "description": "<p>库位编码</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "detail.stock_box",
            "description": "<p>箱子编码</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "成功示例:",
          "content": "{\"code\":0,\"msg\":\"ok\",\"data\":{\"id\":1,\"stock_in_sn\":\"in_812157766\",\"stock_sn_s\":[\"4_S_A01Z01C01\",\"4_S_A01Z01C01\",\"4_S_A01Z01C01\",\"4_S_A01Z01C01\",\"4_S_A01Z01C01\"],\"has_box_num\":9,\"detail\":[{\"stock_sn\":\"4_S_A01Z01C01\",\"box_sn\":[\"CKXH1802251111\",\"CKXH1802251111\"]},{\"stock_sn\":\"4_S_A01Z01C01\",\"box_sn\":[\"CKXH1802251111\",\"CKXH1802251111\"]},{\"stock_sn\":\"4_S_A01Z01C01\",\"box_sn\":[\"CKXH1802251111\",\"CKXH1802251111\"]},{\"stock_sn\":\"4_S_A01Z01C01\",\"box_sn\":[\"CKXH1802251111\",\"CKXH1802251111\"]},{\"stock_sn\":\"4_S_A01Z01C01\",\"box_sn\":[\"CKXH1802251111\",\"CKXH1802251111\"]}]}}",
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
  },
  {
    "type": "post",
    "url": "/api/pda/submitScan",
    "title": "盘点数据提交",
    "version": "2.0.0",
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
            "description": "<p>类型 1代表盘点 2代表出库 3代表检针 4代表装箱</p>"
          },
          {
            "group": "Parameter",
            "type": "string",
            "optional": false,
            "field": "stock_count_id",
            "description": "<p>若为类型1  必须提供盘点单的id  若类型为3和4  必须提供入库单id  stock_in_id</p>"
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
            "field": "element",
            "description": "<p>扫描的编码</p>"
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
            "field": "element",
            "description": "<p>扫描的编码</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "element.fashion_code",
            "description": "<p>扫描的编码</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "element.fashion_size",
            "description": "<p>扫描的尺码</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "element.fashion_num",
            "description": "<p>扫描的数量</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "成功示例:",
          "content": "{\"code\":0,\"msg\":\"ok\",\"data\":[{\"container\":\"daddafffdsfsd\",\"element\":[{\"fashion_code\":\"07M10008A\",\"fashion_size\":\"180\",\"fashion_num\":2}]},{\"container\":\"daddafffdsfsd\",\"element\":[{\"fashion_code\":\"07M10008A\",\"fashion_size\":\"180\",\"fashion_num\":1}]}]}",
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
  },
  {
    "type": "get",
    "url": "/api/addBox",
    "title": "添加箱子",
    "version": "1.0.0",
    "name": "addBox",
    "group": "group_so",
    "permission": [
      {
        "name": "所有的人"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "box_num",
            "description": "<p>需要添加的箱子数量</p>"
          }
        ]
      }
    },
    "description": "<p>添加箱子api</p>",
    "sampleRequest": [
      {
        "url": "/api/addBox"
      }
    ],
    "success": {
      "fields": {
        "返回值": [
          {
            "group": "返回值",
            "type": "int",
            "optional": false,
            "field": "code",
            "description": "<p>状态码</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "msg",
            "description": "<p>提示信息</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "成功示例:",
          "content": "{\"code\":0,\"msg\":\"ok\",\"data\":null}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "(json) 错误示例:",
          "content": "{\"code\":1,\"msg\":\"\\u8bf7\\u8f93\\u5165\\u6b63\\u786e\\u7684\\u7bb1\\u5b50\\u6570\\u91cf\",\"data\":null}",
          "type": "json"
        }
      ]
    },
    "filename": "app/Http/Controllers/Api/Storage/BoxManageController.php",
    "groupTitle": "仓储管理模块"
  },
  {
    "type": "post",
    "url": "/api/addStockCount",
    "title": "新增一个盘点单",
    "version": "1.0.0",
    "name": "addStockCount",
    "group": "group_so",
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
            "type": "int",
            "optional": false,
            "field": "title",
            "description": "<p>收货地址信息</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "operate",
            "description": "<p>收货地址信息</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "time",
            "description": "<p>收货地址信息</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "note",
            "description": "<p>收货地址信息</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "stock_name",
            "description": "<p>收货地址信息</p>"
          }
        ]
      }
    },
    "description": "<p>新增一个盘点单api</p>",
    "sampleRequest": [
      {
        "url": "/api/addStockCount"
      }
    ],
    "success": {
      "fields": {
        "返回值": [
          {
            "group": "返回值",
            "type": "int",
            "optional": false,
            "field": "id",
            "description": "<p>盘点单的id</p>"
          },
          {
            "group": "返回值",
            "type": "int",
            "optional": false,
            "field": "stock_count_sn",
            "description": "<p>盘点单的编号</p>"
          },
          {
            "group": "返回值",
            "type": "int",
            "optional": false,
            "field": "stock_name",
            "description": "<p>名称</p>"
          },
          {
            "group": "返回值",
            "type": "int",
            "optional": false,
            "field": "title",
            "description": "<p>标题</p>"
          },
          {
            "group": "返回值",
            "type": "int",
            "optional": false,
            "field": "operate",
            "description": "<p>操作人</p>"
          },
          {
            "group": "返回值",
            "type": "int",
            "optional": false,
            "field": "status",
            "description": "<p>状态</p>"
          },
          {
            "group": "返回值",
            "type": "int",
            "optional": false,
            "field": "note",
            "description": "<p>备注</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "成功示例:",
          "content": "{\"code\":0,\"api_msg\":\"ok\",\"data\":{\"id\":5}}",
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
    "filename": "app/Http/Controllers/Api/Storage/StockController.php",
    "groupTitle": "仓储管理模块"
  },
  {
    "type": "get",
    "url": "/api/alertStockCountFashionNum",
    "title": "修改盘点单产品数量",
    "version": "2.0.0",
    "name": "alertStockCountFashionNum",
    "group": "group_so",
    "permission": [
      {
        "name": "登录用户"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "stock_count_sn",
            "description": "<p>盘点编码</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "stock_id",
            "description": "<p>库位id</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "fashion_code",
            "description": "<p>产品编码</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "fashion_size",
            "description": "<p>产品尺码</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "fashion_num",
            "description": "<p>产品数量</p>"
          }
        ]
      }
    },
    "description": "<p>修改盘点单产品数量api</p>",
    "sampleRequest": [
      {
        "url": "/api/alertStockCountFashionNum"
      }
    ],
    "success": {
      "fields": {
        "返回值": [
          {
            "group": "返回值",
            "type": "int",
            "optional": false,
            "field": "code",
            "description": "<p>状态码</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "msg",
            "description": "<p>提示信息</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "成功示例:",
          "content": "{\"state\":1,\"msg\":\"本库位编码未盘点本库位\",\"data\":[]}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "(json) 错误示例:",
          "content": "{\"state\":\"1\",\"msg\":\"失败\",\"data\":[]}",
          "type": "json"
        }
      ]
    },
    "filename": "app/Http/Controllers/Api/Storage/StockController.php",
    "groupTitle": "仓储管理模块"
  },
  {
    "type": "get",
    "url": "/api/boxList",
    "title": "箱子列表 (id字段改变 12.10)",
    "version": "2.0.0",
    "name": "boxList",
    "group": "group_so",
    "permission": [
      {
        "name": "所有用户"
      }
    ],
    "description": "<p>箱子列表api</p>",
    "sampleRequest": [
      {
        "url": "/api/boxList"
      }
    ],
    "success": {
      "fields": {
        "返回值": [
          {
            "group": "返回值",
            "type": "int",
            "optional": false,
            "field": "id",
            "description": "<p>箱子的id</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "box_sn",
            "description": "<p>箱子的编号</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "created_at",
            "description": "<p>创建时间</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "成功示例:",
          "content": "{\"state\":\"1\",\"msg\":\"ok\",\"data\":{\"current_page\":1,\"prev_page_url\":null,\"next_page_url\":null,\"total\":10,\"data\":[{\"id\":3,\"box_sn\":\"4H-SH-A01-Z01-C01\",\"created_at\":{\"date\":\"2018-11-27 14:11:10.000000\",\"timezone_type\":3,\"timezone\":\"PRC\"}},{\"id\":4,\"box_sn\":\"4H-SH-A01-Z01-C02\",\"created_at\":{\"date\":\"2018-11-27 14:11:10.000000\",\"timezone_type\":3,\"timezone\":\"PRC\"}},{\"id\":5,\"box_sn\":\"B_c0f4\",\"created_at\":{\"date\":\"2018-11-27 14:11:10.000000\",\"timezone_type\":3,\"timezone\":\"PRC\"}},{\"id\":6,\"box_sn\":\"B_3994\",\"created_at\":{\"date\":\"2018-11-27 16:00:13.000000\",\"timezone_type\":3,\"timezone\":\"PRC\"}},{\"id\":7,\"box_sn\":\"B_5f5d\",\"created_at\":{\"date\":\"2018-11-27 16:16:26.000000\",\"timezone_type\":3,\"timezone\":\"PRC\"}},{\"id\":8,\"box_sn\":\"B_6ad4\",\"created_at\":{\"date\":\"2018-12-03 09:28:14.000000\",\"timezone_type\":3,\"timezone\":\"PRC\"}},{\"id\":9,\"box_sn\":\"B_326a\",\"created_at\":{\"date\":\"2018-12-03 09:31:21.000000\",\"timezone_type\":3,\"timezone\":\"PRC\"}},{\"id\":10,\"box_sn\":\"B_4b7f\",\"created_at\":{\"date\":\"2018-12-03 09:32:18.000000\",\"timezone_type\":3,\"timezone\":\"PRC\"}},{\"id\":11,\"box_sn\":\"B_0239\",\"created_at\":{\"date\":\"2018-12-03 09:32:23.000000\",\"timezone_type\":3,\"timezone\":\"PRC\"}},{\"id\":12,\"box_sn\":\"B_984a\",\"created_at\":{\"date\":\"2018-12-10 08:45:42.000000\",\"timezone_type\":3,\"timezone\":\"PRC\"}}]}}",
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
    "filename": "app/Http/Controllers/Api/Storage/BoxManageController.php",
    "groupTitle": "仓储管理模块"
  },
  {
    "type": "get",
    "url": "/api/delBox",
    "title": "删除一个箱子",
    "version": "1.0.0",
    "name": "delBox",
    "group": "group_so",
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
            "type": "int",
            "optional": false,
            "field": "box_id",
            "description": "<p>箱子的id</p>"
          }
        ]
      }
    },
    "description": "<p>删除箱子api</p>",
    "sampleRequest": [
      {
        "url": "/api/delBox"
      }
    ],
    "success": {
      "fields": {
        "返回值": [
          {
            "group": "返回值",
            "type": "int",
            "optional": false,
            "field": "code",
            "description": "<p>状态码</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "msg",
            "description": "<p>提示信息</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "成功示例:",
          "content": "{\"code\":1,\"msg\":\"\\u4e0d\\u5b58\\u5728\\u7684\\u7bb1\\u5b50\",\"data\":null}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "(json) 错误示例:",
          "content": "{\"code\":1,\"msg\":\"\\u4e0d\\u5b58\\u5728\\u7684\\u7bb1\\u5b50\",\"data\":null}",
          "type": "json"
        }
      ]
    },
    "filename": "app/Http/Controllers/Api/Storage/BoxManageController.php",
    "groupTitle": "仓储管理模块"
  },
  {
    "type": "get",
    "url": "/api/export",
    "title": "按照学校或者产品名称导出产品库位库存信息",
    "version": "2.0.0",
    "name": "export",
    "group": "group_so",
    "permission": [
      {
        "name": "登录用户"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "fashion_code",
            "description": "<p>产品编码</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "school_name",
            "description": "<p>学校名称</p>"
          }
        ]
      }
    },
    "description": "<p>按照学校或者产品名称导出产品库位库存信息api</p>",
    "sampleRequest": [
      {
        "url": "/api/export"
      }
    ],
    "success": {
      "fields": {
        "返回值": [
          {
            "group": "返回值",
            "type": "int",
            "optional": false,
            "field": "code",
            "description": "<p>状态码</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "msg",
            "description": "<p>提示信息</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "成功示例:",
          "content": "{\"code\":1,\"msg\":\"所选地址不存在\",\"data\":[]}",
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
    "filename": "app/Http/Controllers/Api/Storage/StockController.php",
    "groupTitle": "仓储管理模块"
  },
  {
    "type": "get",
    "url": "/api/fashionList",
    "title": "库存查询列表",
    "version": "1.0.0",
    "name": "fashionList",
    "group": "group_so",
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
            "type": "int",
            "optional": false,
            "field": "page",
            "description": "<p>页数</p>"
          }
        ]
      }
    },
    "description": "<p>产品列表api</p>",
    "sampleRequest": [
      {
        "url": "/api/fashionList"
      }
    ],
    "success": {
      "fields": {
        "返回值": [
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "code",
            "description": "<p>产品编码</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "api_msg",
            "description": "<p>学校</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "成功示例:",
          "content": "{\"code\":0,\"api_msg\":\"ok\",\"data\":{\"current_page\":1,\"data\":[{\"code\":\"07M10008A\",\"school\":\"\\u4e0a\\u6d77\\u5e02\\u5929\\u5c71\\u4e2d\\u5b66\",\"real_name\":\"\\u7eaf\\u8272\\u57fa\\u672c\\u6b3e\\u5f00\\u895f\\u957fT\",\"old_code\":\"\",\"style_name\":\"CT09\",\"color\":\"\\u85cf\\u9752,\\u767d\\u8272\",\"pattern_name\":\"2011CT\"}],\"first_page_url\":\"http:\\/\\/myscan.dev.com\\/api\\/fashionList?page=1\",\"from\":1,\"last_page\":6176,\"last_page_url\":\"http:\\/\\/myscan.dev.com\\/api\\/fashionList?page=6176\",\"next_page_url\":\"http:\\/\\/myscan.dev.com\\/api\\/fashionList?page=2\",\"path\":\"http:\\/\\/myscan.dev.com\\/api\\/fashionList\",\"per_page\":1,\"prev_page_url\":null,\"to\":1,\"total\":6176}}",
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
    "filename": "app/Http/Controllers/Api/Storage/StockController.php",
    "groupTitle": "仓储管理模块"
  },
  {
    "type": "get",
    "url": "/api/stockList",
    "title": "库位列表",
    "version": "1.0.0",
    "name": "locationList",
    "group": "group_so",
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
            "type": "int",
            "optional": false,
            "field": "floor",
            "description": "<p>楼层 3或者4</p>"
          },
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "type",
            "description": "<p>类型 1货架2托盘</p>"
          }
        ]
      }
    },
    "description": "<p>库位列表api</p>",
    "sampleRequest": [
      {
        "url": "/api/stockList"
      }
    ],
    "success": {
      "fields": {
        "返回值": [
          {
            "group": "返回值",
            "type": "int",
            "optional": false,
            "field": "id",
            "description": "<p>仓库id</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "stock_sn",
            "description": "<p>仓库编码</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "成功示例:",
          "content": "{\"code\":0,\"api_msg\":\"ok\",\"data\":[{\"stock_id\":1,\"stock_sn\":\"A01-Z01-C01\"}]}",
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
    "filename": "app/Http/Controllers/Api/Storage/StockController.php",
    "groupTitle": "仓储管理模块"
  },
  {
    "type": "get",
    "url": "/api/queryStock",
    "title": "产品库存详情(字段有更改)",
    "version": "2.0.0",
    "name": "queryStock",
    "group": "group_so",
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
            "type": "int",
            "optional": false,
            "field": "fashion_code",
            "description": "<p>产品编码</p>"
          }
        ]
      }
    },
    "description": "<p>查询一个产品的库存api</p>",
    "sampleRequest": [
      {
        "url": "/api/queryStock"
      }
    ],
    "success": {
      "fields": {
        "返回值": [
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "fashion_stock",
            "description": "<p>库存字段</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "fashion_info",
            "description": "<p>产品信息字段</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "x_h_stock",
            "description": "<p>现货库存</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "z_t_stock",
            "description": "<p>在途库存</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "d_j_stock",
            "description": "<p>冻结库存</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "fashion_info.code",
            "description": "<p>编码</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "fashion_info.school",
            "description": "<p>学校</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "fashion_info.real_name",
            "description": "<p>产品名称</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "fashion_info.old_code",
            "description": "<p>关联编码</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "fashion_info.style_name",
            "description": "<p>款式号</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "fashion_info.color",
            "description": "<p>颜色</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "fashion_info.pattern_name",
            "description": "<p>类别</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "fashion_name",
            "description": "<p>产品名称</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "fashion_code",
            "description": "<p>产品编码</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "fashion_size",
            "description": "<p>产品尺码</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "fashion_num",
            "description": "<p>产品数量</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "box_sn",
            "description": "<p>箱子编号</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "stock",
            "description": "<p>库位</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "stock.stock_sn",
            "description": "<p>库位编码</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "stock.stock_name",
            "description": "<p>库位名称</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "stock.floor",
            "description": "<p>库位楼层</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "成功示例:",
          "content": "{\"state\":0,\"msg\":\"ok\",\"data\":{\"fashion_stock\":{\"x_h_stock\":[{\"fashion_name\":null,\"fashion_code\":\"07M10008A\",\"fashion_size\":\"180\",\"fashion_num\":3,\"box_sn\":\"CKXH201896c728\",\"stock\":{\"stock_sn\":\"4-SA01Z01C01\",\"stock_name\":\"4\\u697c\\u4e0a\\u6d77\\u4ed3\",\"floor\":4}},{\"fashion_name\":null,\"fashion_code\":\"07M10008A\",\"fashion_size\":\"190\",\"fashion_num\":2,\"box_sn\":\"CKXH201896c728\",\"stock\":{\"stock_sn\":\"4-SA01Z01C01\",\"stock_name\":\"4\\u697c\\u4e0a\\u6d77\\u4ed3\",\"floor\":4}},{\"fashion_name\":null,\"fashion_code\":\"07M10008A\",\"fashion_size\":\"180\",\"fashion_num\":6,\"box_sn\":null,\"stock\":{\"stock_sn\":\"4-SA01Z01C01\",\"stock_name\":\"4\\u697c\\u4e0a\\u6d77\\u4ed3\",\"floor\":4}},{\"fashion_name\":null,\"fashion_code\":\"07M10008A\",\"fashion_size\":\"190\",\"fashion_num\":1,\"box_sn\":null,\"stock\":{\"stock_sn\":\"4-SA01Z01C01\",\"stock_name\":\"4\\u697c\\u4e0a\\u6d77\\u4ed3\",\"floor\":4}}],\"z_t_stock\":[],\"d_j_stock\":[]},\"fashion_info\":{\"code\":\"07M10008A\",\"school\":\"\\u4e0a\\u6d77\\u5e02\\u5929\\u5c71\\u4e2d\\u5b66\",\"real_name\":\"\\u7eaf\\u8272\\u57fa\\u672c\\u6b3e\\u5f00\\u895f\\u957fT\",\"old_code\":\"\",\"style_name\":\"CT09\",\"color\":\"\\u85cf\\u9752,\\u767d\\u8272\",\"pattern_name\":\"2011CT\"}}}",
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
    "filename": "app/Http/Controllers/Api/Storage/StockController.php",
    "groupTitle": "仓储管理模块"
  },
  {
    "type": "get",
    "url": "/api/startStockCount",
    "title": "启动一个盘点单",
    "version": "1.0.0",
    "name": "startStockCount",
    "group": "group_so",
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
            "type": "int",
            "optional": false,
            "field": "stock_count_id",
            "description": "<p>盘带单id</p>"
          }
        ]
      }
    },
    "description": "<p>开启一个盘点单api</p>",
    "sampleRequest": [
      {
        "url": "/api/startStockCount"
      }
    ],
    "success": {
      "fields": {
        "返回值": [
          {
            "group": "返回值",
            "type": "int",
            "optional": false,
            "field": "code",
            "description": "<p>状态码</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "api_msg",
            "description": "<p>提示信息</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "成功示例:",
          "content": "{\"code\":0,\"api_msg\":\"ok\",\"data\":null}",
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
    "filename": "app/Http/Controllers/Api/Storage/StockController.php",
    "groupTitle": "仓储管理模块"
  },
  {
    "type": "get",
    "url": "/api/stockCountDetail",
    "title": "盘点的详情 (字段有所改变 12.10)",
    "version": "2.0.0",
    "name": "stockCountDetail",
    "group": "group_so",
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
            "type": "int",
            "optional": false,
            "field": "stock_count_id",
            "description": "<p>盘点单的id</p>"
          }
        ]
      }
    },
    "description": "<p>盘点单列表点击进去查看盘点单的详情产品信息api</p>",
    "sampleRequest": [
      {
        "url": "/api/stockCountDetail"
      }
    ],
    "success": {
      "fields": {
        "返回值": [
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "fashion_code",
            "description": "<p>产品编码</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "fashion_name",
            "description": "<p>产品名称</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "fashion_size",
            "description": "<p>产品尺码</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "fashion_num",
            "description": "<p>产品数量</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "school",
            "description": "<p>提学校名称</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "before_fashion_num",
            "description": "<p>账面数量</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "成功示例:",
          "content": "{\"state\":1,\"msg\":\"ok\",\"data\":{\"current_page\":1,\"prev_page_url\":null,\"next_page_url\":\"http:\\/\\/myscan.dev.com\\/api\\/stockCountDetail?page=2\",\"total\":2,\"data\":[{\"fashion_code\":\"07M10008A\",\"fashion_name\":null,\"fashion_size\":\"180\",\"fashion_num\":2,\"before_fashion_num\":0,\"school\":\"\\u4e0a\\u6d77\\u5e02\\u5929\\u5c71\\u4e2d\\u5b66\"},{\"fashion_code\":\"07M10008A\",\"fashion_name\":null,\"fashion_size\":\"180\",\"fashion_num\":3,\"before_fashion_num\":5,\"school\":\"\\u4e0a\\u6d77\\u5e02\\u5929\\u5c71\\u4e2d\\u5b66\"}]}}",
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
    "filename": "app/Http/Controllers/Api/Storage/StockController.php",
    "groupTitle": "仓储管理模块"
  },
  {
    "type": "get",
    "url": "/api/stockCountInfo",
    "title": "盘点单信息",
    "version": "1.0.0",
    "name": "stockCountInfo",
    "group": "group_so",
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
            "type": "int",
            "optional": false,
            "field": "stock_count_id",
            "description": "<p>盘点单的id</p>"
          }
        ]
      }
    },
    "description": "<p>一个盘点单的信息api</p>",
    "sampleRequest": [
      {
        "url": "/api/stockCountInfo"
      }
    ],
    "success": {
      "fields": {
        "返回值": [
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "stock_count_sn",
            "description": "<p>盘点编码</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "stock_name",
            "description": "<p>盘点名称</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "title",
            "description": "<p>盘点标题</p>"
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
            "description": "<p>创建时间</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "note",
            "description": "<p>备注</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "成功示例:",
          "content": "{\"code\":0,\"api_msg\":\"ok\",\"data\":{\"stock_count_sn\":\"pd_812055670\",\"stock_name\":\"\\u4e0a\\u6d77\\u4ed3\",\"title\":\"2018\\u5e744\\u697c\\u4e0a\\u6d77\\u4ed3\\u5e74\\u7ec8\\u76d8\\u70b9\",\"operate\":\"\\u9648\\u7fd4\\u5b87\",\"status\":null,\"created_at\":{\"date\":\"2018-11-20 00:00:00.000000\",\"timezone_type\":3,\"timezone\":\"PRC\"},\"note\":\"\\u7b2c\\u4e00\\u6b21\\u6d4b\\u8bd5\\u5907\\u6ce8\"}}",
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
    "filename": "app/Http/Controllers/Api/Storage/StockController.php",
    "groupTitle": "仓储管理模块"
  },
  {
    "type": "get",
    "url": "/api/stockCountList",
    "title": "盘点单管理",
    "version": "1.0.0",
    "name": "stockCountList",
    "group": "group_so",
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
          "content": "{\"code\":0,\"api_msg\":\"ok\",\"data\":{\"current_page\":1,\"prev_page_url\":null,\"next_page_url\":\"http:\\/\\/myscan.dev.com\\/api\\/stockCountList?page=2\",\"total\":2,\"data\":[{\"stock_count_sn\":\"123\",\"stock_name\":1,\"title\":\"\\u7b2c\\u4e00\\u6b21\\u76d8\\u70b9\",\"operate\":\"\\u9648\\u7fd4\\u5b87\",\"status\":\"\\u672a\\u542f\\u52a8\",\"created_at\":{\"date\":\"2018-12-03 17:47:27.000000\",\"timezone_type\":3,\"timezone\":\"PRC\"}}]}}",
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
    "filename": "app/Http/Controllers/Api/Storage/StockController.php",
    "groupTitle": "仓储管理模块"
  },
  {
    "type": "get",
    "url": "/api/stockCountListDetail",
    "title": "盘点清单(字段有所改变 12.10)",
    "version": "1.0.0",
    "name": "stockCountListDetail",
    "group": "group_so",
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
            "type": "int",
            "optional": false,
            "field": "stock_count_id",
            "description": "<p>盘点的id</p>"
          }
        ]
      }
    },
    "description": "<p>盘点清单api</p>",
    "sampleRequest": [
      {
        "url": "/api/stockCountListDetail"
      }
    ],
    "success": {
      "fields": {
        "返回值": [
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "fashion_code",
            "description": "<p>产品编码</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "fashion_name",
            "description": "<p>产品名称</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "fashion_size",
            "description": "<p>产品尺码</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "fashion_num",
            "description": "<p>产品数量</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "school",
            "description": "<p>学校名称</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "stock.stock_sn",
            "description": "<p>库位编码</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "stock.stock_name",
            "description": "<p>库位名称</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "stock.floor",
            "description": "<p>库位所在楼层</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "stock.type",
            "description": "<p>库位类型 托盘或者货架</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "stock_box.box_sn",
            "description": "<p>所属箱子的编号</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "成功示例:",
          "content": "{\"state\":1,\"msg\":\"ok\",\"data\":{\"fashion_code\":\"07M10008A\",\"fashion_name\":\"\\u7eaf\\u8272\\u57fa\\u672c\\u6b3e\\u5f00\\u895f\\u957fT\",\"fashion_size\":\"180\",\"fashion_num\":25,\"school_name\":null,\"stock\":{\"stock_sn\":\"A01-Z01-C01\",\"stock_name\":\"A\\u533a01\",\"floor\":4,\"type\":\"\\u8d27\\u67b6\"},\"stock_box\":{\"box_sn\":\"B_c07b\"}}}",
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
    "filename": "app/Http/Controllers/Api/Storage/StockController.php",
    "groupTitle": "仓储管理模块"
  },
  {
    "type": "get",
    "url": "/api/stockDetail",
    "title": "库位号里面的详情展示",
    "version": "1.0.0",
    "name": "stockDetail",
    "group": "group_so",
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
            "type": "int",
            "optional": false,
            "field": "stock_id",
            "description": "<p>库位或者托盘的id</p>"
          }
        ]
      }
    },
    "description": "<p>库位管理api</p>",
    "sampleRequest": [
      {
        "url": "/api/stockDetail"
      }
    ],
    "success": {
      "fields": {
        "返回值": [
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "fashion_name",
            "description": "<p>产品名称</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "fashion_code",
            "description": "<p>产品编码</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "fashion_size",
            "description": "<p>产品尺码</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "fashion_num",
            "description": "<p>产品数量</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "school_name",
            "description": "<p>学校名称</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "box_sn",
            "description": "<p>箱号编码</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "成功示例:",
          "content": "{\"code\":0,\"api_msg\":\"ok\",\"data\":[{\"fashion_name\":\"PE\\u8fd0\\u52a8\\u88e4\",\"fashion_code\":\"T1807040A\",\"fashion_size\":\"180\",\"fashion_num\":5,\"school_name\":\"\\u60e0\\u7075\\u987f\\u56fd\\u9645\\u5b66\\u6821\",\"box_sn\":\"\"},{\"fashion_name\":\"PE\\u8fd0\\u52a8\\u88e4\",\"fashion_code\":\"T1807040A\",\"fashion_size\":\"190\",\"fashion_num\":5,\"school_name\":\"\\u60e0\\u7075\\u987f\\u56fd\\u9645\\u5b66\\u6821\",\"box_sn\":\"\"},{\"fashion_name\":\"PE\\u8fd0\\u52a8\\u88e4\",\"fashion_code\":\"T1807040A\",\"fashion_size\":\"170\",\"fashion_num\":5,\"school_name\":\"\\u60e0\\u7075\\u987f\\u56fd\\u9645\\u5b66\\u6821\",\"box_sn\":\"\"}]}",
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
    "filename": "app/Http/Controllers/Api/Storage/StockController.php",
    "groupTitle": "仓储管理模块"
  },
  {
    "type": "get",
    "url": "/api/stockInList",
    "title": "入库单列表",
    "version": "1.0.0",
    "name": "stockInList",
    "group": "group_so",
    "permission": [
      {
        "name": "所有用户"
      }
    ],
    "description": "<p>入库单列表api</p>",
    "sampleRequest": [
      {
        "url": "/api/stockInList"
      }
    ],
    "success": {
      "fields": {
        "返回值": [
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "stock_in_sn",
            "description": "<p>入库编码</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "name",
            "description": "<p>入库名称</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "type",
            "description": "<p>入库类型</p>"
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
            "field": "created_at",
            "description": "<p>操作日期</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "status",
            "description": "<p>审核状态</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "成功示例:",
          "content": "{\"state\":1,\"msg\":\"ok\",\"data\":{\"current_page\":1,\"prev_page_url\":null,\"next_page_url\":\"http:\\/\\/myscan.dev.com\\/api\\/stockInList?page=2\",\"total\":2,\"data\":[{\"stock_in_sn\":\"123\",\"name\":\"\\u7b2c\\u4e00\\u6b21\\u5165\\u5e93\",\"type\":\"\\u9000\\u8d27\\u5165\\u5e93\",\"num\":\"50\",\"operate\":\"\\u9648\\u7fd4\\u5b87\",\"created_at\":null,\"status\":\"\\u5f85\\u5ba1\\u6838\"}]}}",
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
    "filename": "app/Http/Controllers/Api/Storage/StockController.php",
    "groupTitle": "仓储管理模块"
  },
  {
    "type": "get",
    "url": "/api/stockInListDetail",
    "title": "入库单详情(字段有所改变)",
    "version": "1.0.0",
    "name": "stockInListDetail",
    "group": "group_so",
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
            "type": "int",
            "optional": false,
            "field": "stock_in_id",
            "description": "<p>入库单id</p>"
          }
        ]
      }
    },
    "description": "<p>入库单详情api</p>",
    "sampleRequest": [
      {
        "url": "/api/stockInListDetail"
      }
    ],
    "success": {
      "fields": {
        "返回值": [
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "fashion_code",
            "description": "<p>产品编码</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "fashion_name",
            "description": "<p>产品名称</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "fashion_size",
            "description": "<p>产品尺码</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "fashion_num",
            "description": "<p>产品数量</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "school_name",
            "description": "<p>学校名称</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "stock.stock_sn",
            "description": "<p>库位编码</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "stock.stock_name",
            "description": "<p>库位名称</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "stock.floor",
            "description": "<p>库位所在楼层</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "stock.type",
            "description": "<p>库位类型</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "成功示例:",
          "content": "{\"state\":1,\"msg\":\"ok\",\"data\":[{\"fashion_code\":\"07M10008A\",\"fashion_name\":\"\\u7eaf\\u8272\\u57fa\\u672c\\u6b3e\\u5f00\\u895f\\u957fT\",\"fashion_size\":\"120\",\"fashion_num\":5,\"school_name\":null,\"stock\":{\"stock_sn\":\"A01-Z01-C01\",\"stock_name\":\"A\\u533a01\",\"floor\":4,\"type\":\"\\u8d27\\u67b6\"}},{\"fashion_code\":\"07M10008A\",\"fashion_name\":\"\\u7eaf\\u8272\\u57fa\\u672c\\u6b3e\\u5f00\\u895f\\u957fT\",\"fashion_size\":\"150\",\"fashion_num\":10,\"school_name\":null,\"stock\":{\"stock_sn\":\"A01-Z01-C01\",\"stock_name\":\"A\\u533a01\",\"floor\":4,\"type\":\"\\u8d27\\u67b6\"}}]}",
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
    "filename": "app/Http/Controllers/Api/Storage/StockController.php",
    "groupTitle": "仓储管理模块"
  },
  {
    "type": "get",
    "url": "/api/stockInListInfo",
    "title": "入库单信息 (去除num字段)",
    "version": "2.0.0",
    "name": "stockInListInfo",
    "group": "group_so",
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
            "type": "int",
            "optional": false,
            "field": "stock_in_id",
            "description": "<p>入库单id</p>"
          }
        ]
      }
    },
    "description": "<p>入库单详情api</p>",
    "sampleRequest": [
      {
        "url": "/api/stockInListInfo"
      }
    ],
    "success": {
      "fields": {
        "返回值": [
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "stock_in_sn",
            "description": "<p>入库单编码</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "name",
            "description": "<p>入库单名称</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "type",
            "description": "<p>入库类型</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "num",
            "description": "<p>入库数量</p>"
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
            "field": "created_at",
            "description": "<p>操作时间</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "成功示例:",
          "content": "{\"code\":0,\"api_msg\":\"ok\",\"data\":{\"stock_in_sn\":\"123\",\"name\":\"\\u7b2c\\u4e00\\u6b21\\u5165\\u5e93\",\"type\":\"\\u9000\\u8d27\\u5165\\u5e93\",\"num\":\"50\",\"operate\":\"\\u9648\\u7fd4\\u5b87\",\"created_at\":null}}",
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
    "filename": "app/Http/Controllers/Api/Storage/StockController.php",
    "groupTitle": "仓储管理模块"
  },
  {
    "type": "get",
    "url": "/api/stockInRecord",
    "title": "产品入库流水  (字段改变 12.10)",
    "version": "2.0.0",
    "name": "stockInRecord",
    "group": "group_so",
    "permission": [
      {
        "name": "所有用户"
      }
    ],
    "description": "<p>产品入库流水api</p>",
    "sampleRequest": [
      {
        "url": "/api/stockInRecord"
      }
    ],
    "success": {
      "fields": {
        "返回值": [
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "id",
            "description": "<p>id</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "fashion_code",
            "description": "<p>产品编码</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "fashion_name",
            "description": "<p>产品名称</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "fashion_size",
            "description": "<p>产品编码</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "in_num",
            "description": "<p>入库数量</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "stock_in.stock_in_sn",
            "description": "<p>流水编号</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "stock_in.operate",
            "description": "<p>操作人员</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "stock_in.type",
            "description": "<p>类型</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "成功示例:",
          "content": "{\"code\":0,\"api_msg\":\"ok\",\"data\":{\"current_page\":1,\"prev_page_url\":null,\"next_page_url\":\"http:\\/\\/myscan.dev.com\\/api\\/stockInRecord?page=2\",\"total\":2,\"data\":[{\"id\":1,\"fashion_code\":\"07M10008A\",\"fashion_name\":\"\\u7eaf\\u8272\\u57fa\\u672c\\u6b3e\\u5f00\\u895f\\u957fT\",\"fashion_size\":\"120\",\"fashion_num\":5,\"stock_in\":{\"stock_in_sn\":\"123\",\"type\":\"\\u9000\\u8d27\\u5165\\u5e93\",\"operate\":\"\\u9648\\u7fd4\\u5b87\"}}]}}",
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
    "filename": "app/Http/Controllers/Api/Storage/StockController.php",
    "groupTitle": "仓储管理模块"
  },
  {
    "type": "get",
    "url": "/api/stockOutList",
    "title": "出库单列表",
    "version": "1.0.0",
    "name": "stockOutList",
    "group": "group_so",
    "permission": [
      {
        "name": "所有用户"
      }
    ],
    "description": "<p>出库单列表api</p>",
    "sampleRequest": [
      {
        "url": "/api/stockOutList"
      }
    ],
    "success": {
      "fields": {
        "返回值": [
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "stock_out_sn",
            "description": "<p>入库编码</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "name",
            "description": "<p>入库名称</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "type",
            "description": "<p>入库类型</p>"
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
            "field": "created_at",
            "description": "<p>操作日期</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "成功示例:",
          "content": "{\"code\":0,\"api_msg\":\"ok\",\"data\":{\"current_page\":1,\"data\":[{\"stock_out_sn\":\"123\",\"name\":\"\\u7b2c\\u4e00\\u6b21\\u5165\\u5e93\",\"type\":\"\\u9000\\u8d27\\u5165\\u5e93\",\"num\":\"50\",\"operate\":\"\\u9648\\u7fd4\\u5b87\",\"created_at\":null}],\"first_page_url\":\"http:\\/\\/myscan.dev.com\\/api\\/stockInList?page=1\",\"from\":1,\"last_page\":2,\"last_page_url\":\"http:\\/\\/myscan.dev.com\\/api\\/stockInList?page=2\",\"next_page_url\":\"http:\\/\\/myscan.dev.com\\/api\\/stockInList?page=2\",\"path\":\"http:\\/\\/myscan.dev.com\\/api\\/stockInList\",\"per_page\":1,\"prev_page_url\":null,\"to\":1,\"total\":2}}",
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
    "filename": "app/Http/Controllers/Api/Storage/StockController.php",
    "groupTitle": "仓储管理模块"
  },
  {
    "type": "get",
    "url": "/api/stockOutListDetail",
    "title": "出库单详情(字段有所改变 12.10)",
    "version": "2.0.0",
    "name": "stockOutListDetail",
    "group": "group_so",
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
            "type": "int",
            "optional": false,
            "field": "stock_out_id",
            "description": "<p>出库单id</p>"
          }
        ]
      }
    },
    "description": "<p>出库单详情api</p>",
    "sampleRequest": [
      {
        "url": "/api/stockOutListDetail"
      }
    ],
    "success": {
      "fields": {
        "返回值": [
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "fashion_code",
            "description": "<p>产品编码</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "fashion_name",
            "description": "<p>产品名称</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "fashion_size",
            "description": "<p>产品尺码</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "fashion_num",
            "description": "<p>产品数量</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "school_name",
            "description": "<p>学校名称</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "stock",
            "description": "<p>库位信息</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "stock.stock_sn",
            "description": "<p>库位编码</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "stock.stock_name",
            "description": "<p>库位名称</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "stock.stock_floor",
            "description": "<p>库位楼层</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "成功示例:",
          "content": "{\"state\":1,\"msg\":\"ok\",\"data\":[{\"fashion_code\":\"07M10008A\",\"fashion_name\":null,\"fashion_size\":\"180\",\"fashion_num\":7,\"stock\":{\"stock_sn\":\"4H-SH-A01-Z01-C01\",\"stock_name\":\"4\\u697c\\u4e0a\\u6d77\\u4ed3\",\"floor\":4},\"school\":\"\\u4e0a\\u6d77\\u5e02\\u5929\\u5c71\\u4e2d\\u5b66\"},{\"fashion_code\":\"07M10008A\",\"fashion_name\":null,\"fashion_size\":\"190\",\"fashion_num\":1,\"stock\":{\"stock_sn\":\"4H-SH-A01-Z01-C01\",\"stock_name\":\"4\\u697c\\u4e0a\\u6d77\\u4ed3\",\"floor\":4},\"school\":\"\\u4e0a\\u6d77\\u5e02\\u5929\\u5c71\\u4e2d\\u5b66\"},{\"fashion_code\":\"T1807040A\",\"fashion_name\":null,\"fashion_size\":\"180\",\"fashion_num\":5,\"school\":\"\\u60e0\\u7075\\u987f\\u56fd\\u9645\\u5b66\\u6821\",\"stock\":{\"stock_sn\":\"4H-SH-A01-Z01-C01\",\"stock_name\":\"4\\u697c\\u4e0a\\u6d77\\u4ed3\",\"floor\":4}}]}",
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
    "filename": "app/Http/Controllers/Api/Storage/StockController.php",
    "groupTitle": "仓储管理模块"
  },
  {
    "type": "get",
    "url": "/api/stockOutListInfo",
    "title": "出库单信息",
    "version": "1.0.0",
    "name": "stockOutListInfo",
    "group": "group_so",
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
            "type": "int",
            "optional": false,
            "field": "stock_out_id",
            "description": "<p>收货地址信息</p>"
          }
        ]
      }
    },
    "description": "<p>出库单信息api</p>",
    "sampleRequest": [
      {
        "url": "/api/stockOutListInfo"
      }
    ],
    "success": {
      "fields": {
        "返回值": [
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "stock_out_sn",
            "description": "<p>出库单编码</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "name",
            "description": "<p>出库单名称</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "type",
            "description": "<p>出库单类型</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "num",
            "description": "<p>出库数量</p>"
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
            "field": "created_at",
            "description": "<p>操作时间</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "成功示例:",
          "content": "{\"code\":0,\"api_msg\":\"ok\",\"data\":{\"stock_out_sn\":\"123\",\"name\":\"\\u7b2c\\u4e00\\u6b21\\u5165\\u5e93\",\"type\":\"\\u9000\\u8d27\\u5165\\u5e93\",\"num\":\"50\",\"operate\":\"\\u9648\\u7fd4\\u5b87\",\"created_at\":null}}",
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
    "filename": "app/Http/Controllers/Api/Storage/StockController.php",
    "groupTitle": "仓储管理模块"
  },
  {
    "type": "get",
    "url": "/api/stockOutRecord",
    "title": "产品出库流水(字段有所更改 12.10)",
    "version": "2.0.0",
    "name": "stockOutRecord",
    "group": "group_so",
    "permission": [
      {
        "name": "所有用户"
      }
    ],
    "description": "<p>产品出库流水api</p>",
    "sampleRequest": [
      {
        "url": "/api/stockOutRecord"
      }
    ],
    "success": {
      "fields": {
        "返回值": [
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "id",
            "description": "<p>流水记录id</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "fashion_code",
            "description": "<p>产品编码</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "fashion_name",
            "description": "<p>产品名称</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "fashion_size",
            "description": "<p>产品编码</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "out_num",
            "description": "<p>出库数量</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "stock_out.stock_out_sn",
            "description": "<p>流水编号</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "stock_out.type",
            "description": "<p>入库类型</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "stock_out.operate",
            "description": "<p>操作人员</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "成功示例:",
          "content": "{\"code\":0,\"api_msg\":\"ok\",\"data\":{\"current_page\":1,\"data\":[{\"id\":1,\"fashion_code\":\"07M10008A\",\"fashion_name\":\"\\u7eaf\\u8272\\u57fa\\u672c\\u6b3e\\u5f00\\u895f\\u957fT\",\"fashion_size\":\"120\",\"fashion_num\":5,\"stock_out\":{\"stock_out_sn\":\"123\",\"type\":\"\\u9000\\u8d27\\u5165\\u5e93\",\"operate\":\"\\u9648\\u7fd4\\u5b87\"}}],\"first_page_url\":\"http:\\/\\/myscan.dev.com\\/api\\/stockOutRecord?page=1\",\"from\":1,\"last_page\":2,\"last_page_url\":\"http:\\/\\/myscan.dev.com\\/api\\/stockOutRecord?page=2\",\"next_page_url\":\"http:\\/\\/myscan.dev.com\\/api\\/stockOutRecord?page=2\",\"path\":\"http:\\/\\/myscan.dev.com\\/api\\/stockOutRecord\",\"per_page\":1,\"prev_page_url\":null,\"to\":1,\"total\":2}}",
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
    "filename": "app/Http/Controllers/Api/Storage/StockController.php",
    "groupTitle": "仓储管理模块"
  },
  {
    "type": "get",
    "url": "/api/verifyStockIn",
    "title": "入库审核详情页面",
    "version": "1.0.0",
    "name": "verifyStockIn",
    "group": "group_so",
    "permission": [
      {
        "name": "登录用户"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "int",
            "optional": false,
            "field": "stock_in_id",
            "description": "<p>入库单id</p>"
          }
        ]
      }
    },
    "description": "<p>入库审核页面api</p>",
    "sampleRequest": [
      {
        "url": "/api/verifyStockIn"
      }
    ],
    "success": {
      "fields": {
        "返回值": [
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "fashion_name",
            "description": "<p>产品名称</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "fashion_code",
            "description": "<p>产品编码</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "fashion_size",
            "description": "<p>产品尺码</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "fashion_num",
            "description": "<p>产品数量</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "o_num",
            "description": "<p>工厂交货数量</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "fashion_info.school",
            "description": "<p>学校</p>"
          },
          {
            "group": "返回值",
            "type": "string",
            "optional": false,
            "field": "fashion_info.fileurl",
            "description": "<p>图片</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "成功示例:",
          "content": "{\"state\":1,\"msg\":\"ok\",\"data\":[{\"fashion_name\":\"PE\\u8fd0\\u52a8\\u88e4\",\"fashion_code\":\"T1807040A\",\"fashion_size\":\"180\",\"fashion_num\":5,\"fashion_info\":{\"school\":\"\\u60e0\\u7075\\u987f\\u56fd\\u9645\\u5b66\\u6821\",\"fileurl\":null},\"o_num\":55}]}",
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
    "filename": "app/Http/Controllers/Api/Storage/StockController.php",
    "groupTitle": "仓储管理模块"
  }
] });
