ALTER TABLE `orders`
  ADD COLUMN `posted_at`  timestamp NULL ON UPDATE CURRENT_TIMESTAMP COMMENT '订单发货时间' AFTER `completed_at`;

ALTER TABLE `orders`
  ADD COLUMN `courier_id`  int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '快递公司ID' AFTER `total_pv`;

ALTER TABLE `orders`
  ADD COLUMN `money`  double(15,2) UNSIGNED NOT NULL DEFAULT 0.00 COMMENT '余额' AFTER `status`;

ALTER TABLE `orders`
  MODIFY COLUMN `remarks`  varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL COMMENT '用户备注' AFTER `user_address`;