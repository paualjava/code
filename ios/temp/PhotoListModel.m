//
//  PhotoListModel.m
//
//  Created by 512644164@qq.com on 2017/09/19.
//  Copyright © 2017年 air. All rights reserved.
//
#import "PhotoListModel.h"
@implementation PhotoListModel
+ (instancetype)detailWithDict:(NSDictionary *)dict
{
    PhotoListModel *detail= [[self alloc]init];
    detail.this_id          = dict[@"id"];
    detail.title            = dict[@"title"];
    detail.pic              = dict[@"pic"];
    detail.cate             = dict[@"cate"];
    detail.url              = dict[@"url"];
    detail.brief            = dict[@"brief"];
    detail.info             = dict[@"info"];
    detail.postdate         = dict[@"postdate"];
    return detail;
}
@end