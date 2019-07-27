
一、offset，limit （offset 设置从哪里开始，limit 设置想要查询多少条数据）

    Model::offset(0)->limit(10)->get();

二、skip，take （sikip 跳过几条，take 取多少条数据）

    Model::skip(3)->take(3)->get()；