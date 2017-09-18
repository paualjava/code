//
//  AboutUsModel.h
//
//  Created by 512644164@qq.com on 2017/09/18.
//  Copyright © 2017年 air. All rights reserved.
//
#import <Foundation/Foundation.h>
@interface AboutUsModel : NSObject
@property(nonatomic,assign) NSInteger this_id;
@property(nonatomic,strong) NSString *title;
@property(nonatomic,strong) NSString *info;
@property(nonatomic,strong) NSString *postdate;
+ (instancetype)detailWithDict:(NSDictionary *)dict;
@end