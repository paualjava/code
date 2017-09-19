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
	[self loadData];
}
-(void) loadData
{
    AFHTTPSessionManager *manger=[AFHTTPSessionManager manager];
    NSString *url_pre=@"photo_list_json_data/detail/";
	NSString *url=[[Tools getSiteUrl] stringByAppendingFormat:@"%@",url_pre];
	url=[[Tools getSiteUrl] stringByAppendingFormat:@"%@",self.this_id];
    [manger GET:url parameters:nil progress:nil success:^(NSURLSessionDataTask * _Nonnull task, id  _Nullable responseObject)
    {
        if([responseObject isKindOfClass:[NSDictionary class]])
        {
            [self parseData:responseObject];
        }
    }
     failure:^(NSURLSessionDataTask * _Nullable task, NSError * _Nonnull error)
    {
    }
     ];
}
-(void) parseData:(NSDictionary *)data
{
	self.DetailModel=[[PhotoListDetailModel alloc] init];
	self.DetailModel.this_id    =[data objectForKey:@"id"];
	self.DetailModel.title      =[data objectForKey:@"title"];
	self.DetailModel.pic        =[data objectForKey:@"pic"];
	self.DetailModel.cate       =[data objectForKey:@"cate"];
	self.DetailModel.url        =[data objectForKey:@"url"];
	self.DetailModel.brief      =[data objectForKey:@"brief"];
	self.DetailModel.info       =[data objectForKey:@"info"];
	self.DetailModel.postdate   =[data objectForKey:@"postdate"];
	[self showInWebView];
}
-(void)showInWebView
{
    NSMutableString *html = [NSMutableString string];
    [html appendString:@"<html>"];
    [html appendString:@"<head>"];
    [html appendFormat:@"<link rel=\"stylesheet\" href=\"%@\">",[[NSBundle mainBundle] URLForResource:@"NewsDetails.css" withExtension:nil]];
    [html appendString:@"</head>"];
    [html appendString:@"<body>"];
    [html appendString:[self touchBody]];
    [html appendString:@"</body>"];
    [html appendString:@"</html>"];
    self.navigationController.navigationBarHidden=NO;
    self.navigationController.navigationBar.translucent=NO;
    self.webView=[[UIWebView alloc] init];
    self.webView.frame=self.view.bounds;
    [self settingTopBar];
    [self.webView loadHTMLString:html baseURL:nil];
    [self.view addSubview:self.webView];
}
- (void)settingTopBar
{
    self.navigationController.navigationBarHidden=NO;
    self.navigationController.navigationBar.translucent=NO;
    self.title=@"个人中心";
}
-(NSString *) touchBody
{
    NSMutableString *body = [NSMutableString string];
    [body appendFormat:@"<div class=\"title\">%@</div>",self.DetailModel.title];
    [body appendFormat:@"<div class=\"time\">%@</div>",self.DetailModel.postdate];
    if (self.DetailModel.info != nil) {
        [body appendString:self.DetailModel.info];
    }
    return body;
}
- (void)didReceiveMemoryWarning {
    [super didReceiveMemoryWarning];
    // Dispose of any resources that can be recreated.
}
@end