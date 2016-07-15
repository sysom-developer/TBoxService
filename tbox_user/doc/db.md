#车捷宝项目数据字典v1.1


##更新记录

* v1.1 增加了保养分类及具体保养信息表，改进了order_items表，增加了order_id；

* v1.0 创建数据字典，并初始化部分基础表；


## 规范约定

* [ ] 中括号内容为选择，非必须；

* 默认表主键为id, 每一个表都必须；

* created_at 为记录创建时间，默认每个表必须；

* updated_at 为记录更新时间，选择添加；


## 其它信息

* Author: wikimo

* Date: 2015.04.27

* 如有疑问，请联系邮箱 [wikimo@qq.com](mailto:wikimo@qq.com) 或 QQ：164153075



## 数据字典  

### brands 品牌表
<table>
	<tr>
		<th>名称</th>
		<th>类型</th>
		<th>描述</th>
	</tr>
	<tr>
		<td>id</td>
		<td>int(10)</td>
		<td>主键</td>
	</tr>
	<tr>
		<td>name</td>
		<td>varchar(30)</td>
		<td>品牌名称</td>
	</tr>
	<tr>
		<td>letter</td>
		<td>varchar(1)</td>
		<td>拼音首字母</td>
	</tr>
</table>

### series 系列表
<table>
	<tr>
		<th>名称</th>
		<th>类型</th>
		<th>描述</th>
	</tr>
	<tr>
		<td>id</td>
		<td>int(10)</td>
		<td>主键</td>
	</tr>
	<tr>
		<td>brand_id</td>
		<td>int(10)</td>
		<td>品牌外键</td>
	</tr>
	<tr>
		<td>name</td>
		<td>varchar(80)</td>
		<td>系列名称</td>
	</tr>
</table>

### models 型号表
<table>
	<tr>
		<th>名称</th>
		<th>类型</th>
		<th>描述</th>
	</tr>
	<tr>
		<td>id</td>
		<td>int(10)</td>
		<td>主键</td>
	</tr>
	<tr>
		<td>brand_id</td>
		<td>int(10)</td>
		<td>品牌外键</td>
	</tr>
	<tr>
		<td>serie_id</td>
		<td>int(10)</td>
		<td>系列外键</td>
	</tr>
	<tr>
		<td>name</td>
		<td>varchar(100)</td>
		<td>系列名称</td>
	</tr>
	<tr>
		<td>car_id</td>
		<td>varchar(50)</td>
		<td>卡拉丁对应的car_id，用于数据抓取</td>
	</tr>
</table>

### maintain_categories 保养分类表
<table>
	<tr>
		<th>名称</th>
		<th>类型</th>
		<th>描述</th>
	</tr>
	<tr>
		<td>id</td>
		<td>smallint(3)</td>
		<td>主键</td>
	</tr>
	<tr>
		<td>name</td>
		<td>varchar(30)</td>
		<td>分类名称，例：空气滤清器，机油</td>
	</tr>
	<tr>
		<td>type_id</td>
		<td>smallint(1)</td>
		<td>保养分类，默认0：小保养；1：大保养</td>
	</tr>
	<tr>
		<td>orderby</td>
		<td>smallint(2)</td>
		<td>排序，默认99</td>
	</tr>
</table>

### maintain_items 保养项目表
<table>
	<tr>
		<th>名称</th>
		<th>类型</th>
		<th>描述</th>
	</tr>
	<tr>
		<td>id</td>
		<td>int(10)</td>
		<td>主键</td>
	</tr>
	<tr>
		<td>model_id</td>
		<td>int(10)</td>
		<td>车辆型号外键</td>
	</tr>
	<tr>
		<td>maintain_category_id</td>
		<td> smallint(3)</td>
		<td>分类外键</td>
	</tr>
	<tr>
		<td>name</td>
		<td>varchar(100)</td>
		<td>保养项名称，例：机油分类下的嘉实多</td>
	</tr>
	<tr>
		<td>price</td>
		<td>double(10,2)</td>
		<td>项目单价</td>
	</tr>
</table>


### users 用户表
<table>
	<tr>
		<th>名称</th>
		<th>类型</th>
		<th>描述</th>
	</tr>
	<tr>
		<td>id</td>
		<td>int(10)</td>
		<td>主键</td>
	</tr>
	<tr>
		<td>phone</td>
		<td>varchar(15)</td>
		<td>手机号</td>
	</tr>
	<tr>
		<td>truename</td>
		<td>varchar(15)</td>
		<td>姓名</td>
	</tr>
	<tr>
		<td>password</td>
		<td>varchar(80)</td>
		<td>密码</td>
	</tr>
	<tr>
		<td>salt</td>
		<td>varchar(15)</td>
		<td>密码盐</td>
	</tr>
	<tr>
		<td>openid</td>
		<td>varchar(50)</td>
		<td>微信openid</td>
	</tr>
	<tr>
		<td>created_at</td>
		<td>int(11)</td>
		<td>用户创建时间</td>
	</tr>
</table>

### address 订单表
<table>
	<tr>
		<th>名称</th>
		<th>类型</th>
		<th>描述</th>
	</tr>
	<tr>
		<td>id</td>
		<td>int(10)</td>
		<td>主键</td>
	</tr>
	<tr>
		<td>user_id</td>
		<td>int(10)</td>
		<td>用户外键</td>
	</tr>
	<tr>
		<td>content</td>
		<td>varchar(150)</td>
		<td>详细地址</td>
	</tr>
</table>

### autos 车辆信息
<table>
	<tr>
		<th>名称</th>
		<th>类型</th>
		<th>描述</th>
	</tr>
	<tr>
		<td>id</td>
		<td>int(10)</td>
		<td>主键</td>
	</tr>
	<tr>
		<td>user_id</td>
		<td>int(10)</td>
		<td>用户外键</td>
	</tr>
	<tr>
		<td>brand_id</td>
		<td>int(10)</td>
		<td>brands外键</td>
	</tr>
	<tr>
		<td>serie_id</td>
		<td>int(10)</td>
		<td>series外键</td>
	</tr>
	<tr>
		<td>model_id</td>
		<td>int(10)</td>
		<td>model外键</td>
	</tr>
	<tr>
		<td>no</td>
		<td>varchar(20)</td>
		<td>车牌</td>
	</tr>
	<tr>
		<td>created_at</td>
		<td>int(11)</td>
		<td>添加时间</td>
	</tr>
</table>

### orders 订单表
<table>
	<tr>
		<th>名称</th>
		<th>类型</th>
		<th>描述</th>
	</tr>
	<tr>
		<td>id</td>
		<td>int(10)</td>
		<td>主键</td>
	</tr>
	<tr>
		<td>user_id</td>
		<td>int(10)</td>
		<td>用户外键</td>
	</tr>
	<tr>
		<td>auto_id</td>
		<td>int(10)</td>
		<td>服务对象，车外键</td>
	</tr>
	<tr>
		<td>type_id</td>
		<td>int(10)</td>
		<td>保养类型(0小保养,1大保养)</td>
	</tr>
	<tr>
		<td>is_ticket</td>
		<td>tinyint(1)</td>
		<td>记录发票抬头</td>
	</tr>
	<tr>
		<td>discount_code</td>
		<td>varchar(15)</td>
		<td>优惠码， 默认空字符</td>
	</tr>
	<tr>
		<td>memo</td>
		<td>varchar(120)</td>
		<td>备注</td>
	</tr>
	<tr>
		<td>phone</td>
		<td>varchar(15)</td>
		<td>预定者电话号码</td>
	</tr>
	<tr>
		<td>truename</td>
		<td>varchar(15)</td>
		<td>预定者名字</td>
	</tr>
	<tr>
		<td>state</td>
		<td>smallint(1)</td>
		<td>订单状态，默认0：已下单，等待确认；1：已确认，等待服务；2：服务中；3：已服务；4：已完成；</td>
	</tr>
	<tr>
		<td>pre_total</td>
		<td>double(10,2)</td>
		<td>优惠前价格</td>
	</tr>
	<tr>
		<td>total</td>
		<td>double(10,2)</td>
		<td>总价</td>
	</tr>
	<tr>
		<td>created_at</td>
		<td>int(11)</td>
		<td>订单创建时间</td>
	</tr>
	<tr>
		<td>serviced_at</td>
		<td>date</td>
		<td>服务日期</td>
	</tr>
	<tr>
		<td>serviced_times</td>
		<td>smallint(1)</td>
		<td>服务时段：0：8：00-12：00，1：12：17：00， 17：00 - 20：00</td>
	</tr>
</table>


### order_items 订单表
<table>
	<tr>
		<th>名称</th>
		<th>类型</th>
		<th>描述</th>
	</tr>
	<tr>
		<td>id</td>
		<td>int(10)</td>
		<td>主键</td>
	</tr>
	<tr>
		<td>user_id</td>
		<td>int(10)</td>
		<td>用户外键</td>
	</tr>
	<tr>
		<td>order_id</td>
		<td>int(10)</td>
		<td>订单外键</td>
	</tr>
	<tr>
		<td>maintain_item_id</td>
		<td>int(10)</td>
		<td>保养具体项外键</td>
	</tr>
</table>

### coupons 优惠券表
<table>
	<tr>
		<th>名称</th>
		<th>类型</th>
		<th>描述</th>
	</tr>
	<tr>
		<td>id</td>
		<td>int(10)</td>
		<td>主键</td>
	</tr>
	<tr>
		<td>name</td>
		<td>varchar(30)</td>
		<td>优惠券名称</td>
	</tr>
	<tr>
		<td>value</td>
		<td>mediumint(5)</td>
		<td>优惠券面值</td>
	</tr>
	<tr>
		<td>start_at</td>
		<td>int(11)</td>
		<td>开始时间</td>
	</tr>
	<tr>
		<td>end_at</td>
		<td>int(11)</td>
		<td>结束时间</td>
	</tr>
	<tr>
		<td>created_at</td>
		<td>int(11)</td>
		<td>优惠券创建时间</td>
	</tr>
</table>


### coupon_users 优惠券用户关系表
<table>
	<tr>
		<th>名称</th>
		<th>类型</th>
		<th>描述</th>
	</tr>
	<tr>
		<td>id</td>
		<td>int(10)</td>
		<td>主键</td>
	</tr>
	<tr>
		<td>user_id</td>
		<td>int(10)</td>
		<td>用户外键</td>
	</tr>
	<tr>
		<td>coupon_id</td>
		<td>int(10)</td>
		<td>优惠券外键</td>
	</tr>
	<tr>
		<td>state</td>
		<td>tinyint(1)</td>
		<td>是否已被使用 默认0：未使用 1：已使用</td>
	</tr>
	<tr>
		<td>created_at</td>
		<td>int(11)</td>
		<td>记录创建时间</td>
	</tr>
</table>