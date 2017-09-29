//
//  tableviewController.m
//  demo_demo
//
//  Created by jiajia on 2017/8/3.
//  Copyright © 2017年 air. All rights reserved.
//

#import "tableviewController.h"
#import "NewsDetailViewController.h"
#import "UIImageView+WebCache.h"
#import "Masonry/Masonry.h"
#import "SDCycleScrollView.h"
#import "Tools.h"

@interface tableviewController ()<SDCycleScrollViewDelegate>
@property(nonatomic,strong)NSArray * arrayList;
@property(nonatomic,strong)UIScrollView * scrollView;
@property(nonatomic,strong)SDCycleScrollView *cycleScrollView2;
@end

@implementation tableviewController

- (void)viewDidLoad {
    [super viewDidLoad];
       //[self.tableView mas_makeConstraints:^(MASConstraintMaker *make) {
    ////make.edges.mas_equalTo(self.view);
   // }];
    self.scrollView = [[UIScrollView alloc] init];
    self.scrollView.pagingEnabled = NO;
    [self.view addSubview:self.scrollView];
    self.scrollView.backgroundColor = [UIColor lightGrayColor];
    
    self.navigationController.navigationBar.translucent=NO;
    self.navigationItem.title=@"首页";
    //滚动图片
	[self slidePic];
	//4个icon
	int padding1=15;
    UIImageView *icon_image1=[[UIImageView alloc] init];
    NSString *urlPic_str=[[Tools getSiteUrl] stringByAppendingString:@"upload/icon/index_icon1.png"];
    NSURL *urlPic=[NSURL URLWithString:urlPic_str];
    [icon_image1 sd_setImageWithURL:urlPic placeholderImage:[UIImage imageNamed:@"icon_set"]];
    [self.scrollView addSubview:icon_image1];
    [icon_image1 mas_makeConstraints:^(MASConstraintMaker *make) {
        make.size.mas_equalTo(CGSizeMake(60, 60));
		make.centerY.mas_equalTo(self.view.mas_centerY);
        make.top.mas_equalTo(self.cycleScrollView2.mas_top).with.offset(padding1);
        make.left.mas_equalTo(padding1);
		make.right.mas_equalTo(icon_image2.mas_left).with.offset(-padding1);
    }];
    UIImageView *icon_image2=[[UIImageView alloc] init];
    NSString *urlPic2_str=[[Tools getSiteUrl] stringByAppendingString:@"upload/icon/index_icon2.png"];
    NSURL *urlPic2=[NSURL URLWithString:urlPic2_str];
    [icon_image2 sd_setImageWithURL:urlPic2 placeholderImage:[UIImage imageNamed:@"icon_set"]];
    [self.scrollView addSubview:icon_image2];
    [icon_image2 mas_makeConstraints:^(MASConstraintMaker *make) {
        make.size.mas_equalTo(CGSizeMake(60, 60));
		make.centerY.mas_equalTo(self.view.mas_centerY);
        make.top.mas_equalTo(self.cycleScrollView2.mas_top).with.offset(padding1);
        make.left.mas_equalTo(icon_image1.mas_right).with.offset(padding1);
		make.right.mas_equalTo(icon_image3.mas_left).with.offset(-padding1);
    }];
	
	UIImageView *icon_image3=[[UIImageView alloc] init];
    NSString *urlPic3_str=[[Tools getSiteUrl] stringByAppendingString:@"upload/icon/index_icon3.png"];
    NSURL *urlPic3=[NSURL URLWithString:urlPic3_str];
    [icon_image3 sd_setImageWithURL:urlPic3 placeholderImage:[UIImage imageNamed:@"icon_set"]];
    [self.scrollView addSubview:icon_image3];
    [icon_image3 mas_makeConstraints:^(MASConstraintMaker *make) {
        make.size.mas_equalTo(CGSizeMake(60, 60));
		make.centerY.mas_equalTo(self.view.mas_centerY);
        make.top.mas_equalTo(self.cycleScrollView2.mas_top).with.offset(padding1);
        make.left.mas_equalTo(icon_image2.mas_right).with.offset(padding1);
		make.right.mas_equalTo(icon_image4.mas_left).with.offset(-padding1);
    }];
	
	UIImageView *icon_image4=[[UIImageView alloc] init];
    NSString *urlPic4_str=[[Tools getSiteUrl] stringByAppendingString:@"upload/icon/index_icon4.png"];
    NSURL *urlPic4=[NSURL URLWithString:urlPic4_str];
    [icon_image4 sd_setImageWithURL:urlPic4 placeholderImage:[UIImage imageNamed:@"icon_set"]];
    [self.scrollView addSubview:icon_image4];
    [icon_image4 mas_makeConstraints:^(MASConstraintMaker *make) {
        make.size.mas_equalTo(CGSizeMake(60, 60));
		make.centerY.mas_equalTo(self.view.mas_centerY);
        make.top.mas_equalTo(self.cycleScrollView2.mas_top).with.offset(padding1);
        make.left.mas_equalTo(icon_image3.mas_right).with.offset(padding1);
		make.right.mas_equalTo(-padding1);
    }];
    //灰色背景图
    UIView *gray_view2=[[UIView alloc] init];
    gray_view2.backgroundColor=[UIColor colorWithRed:240.0f/255.0f green:240.0f/255.0f blue:240.0f/255.0f alpha:0.5];
    [self.scrollView addSubview:gray_view2];
    [gray_view2 mas_makeConstraints:^(MASConstraintMaker *make) {
        make.top.mas_equalTo(icon_image4.mas_top);
        make.left.right.mas_equalTo(0);
		make.height.mas_equalTo(18);
    }];
	//TOD导航 注意 更多要做点击事件
	UILable *lable1=[[UILable alloc] init];
    lable1.font = [UIFont fontWithName:@Arial size:20];
	lable1.text = @"文章浏览";
	label1.textColor = [UIColor blackColor];
    [self.view addSubview:lable1];
    [lable1 mas_makeConstraints:^(MASConstraintMaker *make) {
        make.top.mas_equalTo(gray_view2.mas_top);
		make.size.mas_equalTo(CGSizeMake(120,30));
		make.left.mas_equalTo(padding1);
		make.height.mas_equalTo(25);
    }];
	UILable *lable2=[[UILable alloc] init];
    lable2.font = [UIFont fontWithName:@Arial size:20];
	lable2.text = @"查看全部 >";
	lable2.textColor = [UIColor blackColor];
    [self.view addSubview:lable2];
    [lable2 mas_makeConstraints:^(MASConstraintMaker *make) {
        make.top.mas_equalTo(gray_view2.mas_top);
		make.right.mas_equalTo(padding1);
		make.size.mas_equalTo(CGSizeMake(120,30));
    }];
    //文章列表
	UIImageView *pic_none=nil;
	for(int i=1;i<=5;i++)
	{
		 UIImageView *pic_image=[[UIImageView alloc] init];
		NSString *urlPic_str=[[Tools getSiteUrl] stringWithFormat:@"upload/test/%ld.jpg",i];
		NSURL *urlPic=[NSURL URLWithString:urlPic_str];
		[pic_image sd_setImageWithURL:urlPic placeholderImage:[UIImage imageNamed:@"icon_set"]];
		[self.scrollView addSubview:pic_image];
		[pic_image mas_makeConstraints:^(MASConstraintMaker *make) {
			make.size.mas_equalTo(CGSizeMake(180, 136));
			if(pic_none)
			make.top.mas_equalTo(pic_none.mas_top).with.offset(30);
			else
			make.top.mas_equalTo(lable2.mas_top).with.offset(padding1);
			make.left.mas_equalTo(padding1);
		}];
		pic_none=pic_image;
		UILable *lable3=[[UILable alloc] init];
		lable3.font = [UIFont fontWithName:@Arial size:20];
		lable3.text = @"ps电商设计网站站点设计";
		lable3.textColor = [UIColor blackColor];
		lable3.numberOfLines = 2;
		[self.view addSubview:lable3];
		[lable3 mas_makeConstraints:^(MASConstraintMaker *make) {
			make.top.mas_equalTo(pic_image.mas_top);
			make.right.mas_equalTo(padding1);
			make.left.mas_equalTo(pic_image).with.offset(padding1);
		}];
		UILable *lable4=[[UILable alloc] init];
		lable4.font = [UIFont fontWithName:@Arial size:20];
		lable4.text = @"程序开发";
		lable4.textColor = [UIColor colorWithRed:150.0f/255.0f green:150.0f/255.0f blue:150.0f/255.0f alpha:0.5];
		lable4.numberOfLines = 1;
		[self.view addSubview:lable4];
		[lable4 mas_makeConstraints:^(MASConstraintMaker *make) {
			make.top.mas_equalTo(lable3.mas_top).with.offset(25);
			make.right.mas_equalTo(padding1);
			make.left.mas_equalTo(lable3.mas_left);
		}];
	}
    self.view.backgroundColor=[UIColor whiteColor];
    
    [self.scrollView mas_makeConstraints:^(MASConstraintMaker *make) {
        make.edges.equalTo(self.view);
        
        // 让scrollview的contentSize随着内容的增多而变化
        make.bottom.mas_equalTo(view4.mas_bottom).offset(20);
    }];
   
        // Do any additional setup after loading the view.
}
-(void)slidePic{

 NSArray *imagesURLStrings = @[
                                  @"https://ss2.baidu.com/-vo3dSag_xI4khGko9WTAnF6hhy/super/whfpf%3D425%2C260%2C50/sign=a4b3d7085dee3d6d2293d48b252b5910/0e2442a7d933c89524cd5cd4d51373f0830200ea.jpg",
                                  @"https://timgsa.baidu.com/timg?image&quality=80&size=b9999_10000&sec=1502018651864&di=0fad9e34e08eacb27a9e47541bb200da&imgtype=0&src=http%3A%2F%2Fpic31.nipic.com%2F20130703%2F8089054_113138379103_2.jpg",
                                  @"https://timgsa.baidu.com/timg?image&quality=80&size=b9999_10000&sec=1502018605476&di=976f0bfca24058af144ac2399cc165f8&imgtype=0&src=http%3A%2F%2Fimg.taopic.com%2Fuploads%2Fallimg%2F100518%2F27-10051Q45K30.jpg",
                                   @"https://timgsa.baidu.com/timg?image&quality=80&size=b9999_10000&sec=1502018703816&di=4d7869166980ff7cefdcd341fcda43db&imgtype=0&src=http%3A%2F%2Fimgs.focus.cn%2Fupload%2Fcq%2F36952%2Fa_369514203.jpg"
                                  ];
    // 情景三：图片配文字
    NSArray *titles = @[@"长期境外消费过千元会被外管局重点关注",
                        @"河北一企业将危化废液卖给千余锅炉用户",
                        @"为混入清华大学校园参观 一家四口藏身快递车",
                        @"中国无现金化普及:以惊人的速度进步"
                        ];
						 CGFloat w = self.view.bounds.size.width;
    NSLog(@"图片滚动的宽度是多少%f",w);
    self.cycleScrollView2 = [SDCycleScrollView cycleScrollViewWithFrame:CGRectMake(0, 0, w, 200) delegate:self placeholderImage:[UIImage imageNamed:@"placeholder"]];
    
    self.cycleScrollView2.pageControlAliment = SDCycleScrollViewPageContolAlimentRight;
    self.cycleScrollView2.titlesGroup = titles;
    self.cycleScrollView2.autoScrollTimeInterval=4;
    self.cycleScrollView2.currentPageDotColor = [UIColor whiteColor]; // 自定义分页控件小圆标颜色
    [self.view addSubview:self.cycleScrollView2];
    
    //         --- 模拟加载延迟
    dispatch_after(dispatch_time(DISPATCH_TIME_NOW, (int64_t)(0.3 * NSEC_PER_SEC)), dispatch_get_main_queue(), ^{
        self.cycleScrollView2.imageURLStringsGroup = imagesURLStrings;
    });
}
- (void)didReceiveMemoryWarning {
    [super didReceiveMemoryWarning];
    // Dispose of any resources that can be recreated.
}

/*
#pragma mark - Navigation

// In a storyboard-based application, you will often want to do a little preparation before navigation
- (void)prepareForSegue:(UIStoryboardSegue *)segue sender:(id)sender {
    // Get the new view controller using [segue destinationViewController].
    // Pass the selected object to the new view controller.
}
*/

@end
