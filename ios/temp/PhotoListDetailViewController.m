//
//  PhotoListDetailViewController.m
//
//  Created by 512644164@qq.com on 2017/09/19.
//  Copyright © 2017年 air. All rights reserved.
//
#import "PhotoListDetailViewController.h"
#import "AFNetworking.h"
#import "PhotoListDetailModel.h"
#import "Tools.h"
@interface PhotoListDetailViewController ()
@property(nonatomic,strong) PhotoListDetailModel *DetailModel;
@end
@implementation PhotoListDetailViewController
- (void)viewDidLoad {
    [super viewDidLoad];
	 self.title=@"图片显示";
    [self showPic];
}
-(void) showPic
{
    UIImageView *imageV=[[UIImageView alloc] initWithImage:self.image];
    imageV.frame=[[UIScreen mainScreen] bounds];
    [self.view addSubview:imageV];
}
- (void)didReceiveMemoryWarning {
    [super didReceiveMemoryWarning];
    // Dispose of any resources that can be recreated.
}
@end