//
//  AboutUsViewController.m
//
//  Created by 512644164@qq.com on 2017/09/18.
//  Copyright © 2017年 air. All rights reserved.
//
#import "AboutUsViewController.h"
#import "AFNetworking.h"
#import "UIImageView+WebCache.h"
#import "AboutUsModel.h"
#import "AboutUsDetailViewController.h"
#import "Tools.h"
@interface AboutUsViewController ()<UITableViewDataSource,UITableViewDelegate>
@property(nonatomic,strong) UITableView *tableView;
@property(nonatomic,strong) NSMutableArray *arrayData;
@end
@implementation AboutUsViewController
- (void)viewDidLoad {
    [super viewDidLoad];
	self.title=@"json数据";
    self.tableView=[[UITableView alloc] initWithFrame:self.view.bounds style:UITableViewStyleGrouped];
    self.tableView.dataSource=self;
    self.tableView.delegate=self;
    [self.view addSubview:self.tableView];
    self.arrayData=[[NSMutableArray alloc] init];
    [self loadData];
    // Do any additional setup after loading the view.
}
-(void) loadData
{
    AFHTTPSessionManager *manger=[AFHTTPSessionManager manager];
    NSString *url_pre=@"about_us_json_data";
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
        AboutUsModel *thisModel=[[AboutUsModel alloc] init];
		thisModel.this_id          =[dic objectForKey:@"id"];
		thisModel.title            =[dic objectForKey:@"title"];
		thisModel.postdate         =[dic objectForKey:@"postdate"];
        [self.arrayData addObject:thisModel];
    }
    [self.tableView reloadData];
   // NSLog(@"解析的entry的长度是%ld",entry.count);
}
- (void)didReceiveMemoryWarning {
    [super didReceiveMemoryWarning];
    // Dispose of any resources that can be recreated.
}
-(NSInteger)numberOfSectionsInTableView:(UITableView *)tableView
{
    return 1;
}
-(UITableViewCell *)tableView:(UITableView *)tableView cellForRowAtIndexPath:(NSIndexPath *)indexPath
{
    NSString *iden=@"iden";
    UITableViewCell *cell=[self.tableView dequeueReusableCellWithIdentifier:iden];
    if(cell==nil)
    {
        cell=[[UITableViewCell alloc] initWithStyle:UITableViewCellStyleDefault reuseIdentifier:iden];
    }
	cell.accessoryType=UITableViewCellAccessoryDisclosureIndicator;
    AboutUsModel *thisModel=self.arrayData[indexPath.row];
    cell.textLabel.text=thisModel.title;
    return cell;
}
-(NSInteger)tableView:(UITableView *)tableView numberOfRowsInSection:(NSInteger)section
{
    return self.arrayData.count;
}
-(CGFloat)tableView:(UITableView *)tableView heightForRowAtIndexPath:(NSIndexPath *)indexPath
{
    return 45.0;
}
-(void)tableView:(UITableView *)tableView didSelectRowAtIndexPath:(NSIndexPath *)indexPath
{
	 AboutUsModel *thisModel=self.arrayData[indexPath.row];
	 AboutUsDetailViewController *detailController=[[AboutUsDetailViewController alloc] init];
	 detailController.this_id=thisModel.this_id;
    [self.navigationController pushViewController:detailController animated:YES];
}
@end