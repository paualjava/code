//
//  PhotoListDetailViewController.h
//
//  Created by 512644164@qq.com on 2017/09/19.
//  Copyright © 2017年 air. All rights reserved.
//
#import <UIKit/UIKit.h>
@interface PhotoListDetailViewController : UIViewController
@property(nonatomic,assign) NSInteger this_id;
@property(nonatomic,strong) NSString *pic;
@property(nonatomic,strong) UIWebView *webView;
@end