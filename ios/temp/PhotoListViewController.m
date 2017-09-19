//
//  PhotoListViewController.m
//
//  Created by 512644164@qq.com on 2017/09/19.
//  Copyright © 2017年 air. All rights reserved.
//
#import "PhotoListViewController.h"
#import "AFNetworking.h"
#import "UIImageView+WebCache.h"
#import "PhotoListModel.h"
#import "PhotoListDetailViewController.h"
#import "Tools.h"
@interface PhotoListViewController ()
@property(nonatomic,strong) NSMutableArray *arrayData;
@end
@implementation PhotoListViewController
- (void)viewDidLoad {
    [super viewDidLoad];
	self.title=@"图片列表";
    self.arrayData=[[NSMutableArray alloc] init];
    [self loadData];
}
-(void) loadData
{
    AFHTTPSessionManager *manger=[AFHTTPSessionManager manager];
    NSString *url_pre=@"photo_list_json_data";
	NSString *url=[[Tools getSiteUrl] stringByAppendingFormat:@"%@",url_pre];
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
    NSArray *this_data=[data objectForKey:@"this_data"];
    for(NSDictionary *dic in this_data)
    {
        PhotoListModel *thisModel=[[PhotoListModel alloc] init];
		thisModel.this_id          =[dic objectForKey:@"id"];
		thisModel.title            =[dic objectForKey:@"title"];
		thisModel.pic              =[dic objectForKey:@"pic"];
		thisModel.cate             =[dic objectForKey:@"cate"];
		thisModel.url              =[dic objectForKey:@"url"];
		thisModel.brief            =[dic objectForKey:@"brief"];
		thisModel.postdate         =[dic objectForKey:@"postdate"];
        [self.arrayData addObject:thisModel];
    }
	[self showPic];
}
-(void)showPic
{
    UIScrollView *sView=[[UIScrollView alloc] init];
    sView.frame=[[UIScreen mainScreen] bounds];
    sView.contentSize=CGSizeMake(300, 500*4);
    sView.showsVerticalScrollIndicator=NO;
    for(NSInteger i=0;i<self.arrayData.count;i++)
    {
         PhotoListModel *thisModel=self.arrayData[i];
        NSString *pic=thisModel.pic;
        NSURL *urlPic=[NSURL URLWithString:pic];
          UIImageView *imageView=[[UIImageView alloc] init];
        imageView.frame=CGRectMake(10+(i%3)*120, 5+(i/3)*160, 100, 150);
        [imageView sd_setImageWithURL:urlPic placeholderImage:[UIImage imageNamed:@"icon_set"]];
        
       /* NSString *imageName=[NSString stringWithFormat:@"pic_%ld.jpg",i+1];
        UIImage *image=[UIImage imageNamed:imageName];
        UIImageView *imageV=[[UIImageView alloc] initWithImage:image];
        imageV.frame=CGRectMake(10+(i%3)*120, 5+(i/3)*160, 100, 150);*/
        [sView addSubview:imageView];
        UITapGestureRecognizer *tap=[[UITapGestureRecognizer alloc] initWithTarget:self action:@selector(picShow:)];
        tap.numberOfTapsRequired=1;
        tap.numberOfTouchesRequired=1;
        imageView.userInteractionEnabled=YES;
        [imageView addGestureRecognizer:tap];
    }
    [self.view addSubview:sView];
}
-(void) picShow:(UITapGestureRecognizer *) tap
{
    UIImageView *imageV=(UIImageView *) tap.view;
    PhotoListDetailViewController *imageShow=[[PhotoListDetailViewController alloc] init];
    NSLog(@"%@",imageV.image);
    imageShow.pic=imageV.image;
    [self.navigationController pushViewController:imageShow animated:YES];
}
- (void)didReceiveMemoryWarning {
    [super didReceiveMemoryWarning];
    // Dispose of any resources that can be recreated.
}
@end
