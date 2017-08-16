//
//  LPNewsBaseCellNode.m
//  LovePlayNews
//
//  Created by tany on 16/8/12.
//  Copyright © 2016年 tany. All rights reserved.
//

#import "LPNewsBaseCellNode.h"

@interface LPNewsBaseCellNode ()

@property (nonatomic, strong) LPNewsInfoModel *newsInfo;

@end

@implementation LPNewsBaseCellNode

- (instancetype)initWithNewsInfo:(LPNewsInfoModel *)newsInfo
{
    if (self = [super init]) {
        _newsInfo = newsInfo;
        self.selectionStyle = UITableViewCellSelectionStyleNone;
    }
    return self;
}
-(void) test
{
	UIScrollView *iView=[[UIScrollView] alloc] init];
	iView.contentSize=CGSizeMake(300,650);
	
	for(NSinteger i=1;i<10;i++)
	{
		NSString * image_str=[NSString stringWithFormat:@"pic_%d.jpg",i];
		UIImage *image=[UIImage imageNamed:image_str];
		UIImageView *imageV=[[UIImageView] alloc] initWithImage:image];
		imageV.frame=CGRectMake(3,10,90,120);
		[sv addSubView imageV];
		sv.userInteractionEnable=YES;
	}
}
@end
