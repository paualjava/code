//
//  AboutUsDetailModel.m
//
//  Created by 512644164@qq.com on 2017/09/18.
//  Copyright © 2017年 air. All rights reserved.
//
#import "AboutUsDetailModel.h"
@implementation AboutUsDetailModel
+ (instancetype)detailWithDict:(NSDictionary *)dict
{
    AboutUsDetailModel *detail= [[self alloc]init];
    detail.this_id          = dict[@"id"];
    detail.title            = dict[@"title"];
    detail.info             = dict[@"info"];
    detail.postdate         = dict[@"postdate"];
    return detail;
}
@end