clear;clc;close all;

% data = csvread('ps10_mr10_g100/out10.csv');
% data = csvread('ps10_mr10_g100/out20.csv');
% data = csvread('ps10_mr10_g100/out30.csv');
data = csvread('ps50_mr10_g100.csv');
% data = csvread('out.csv');
meanClickRates = data(2:end,1);

population = data(:,2:end);

figure,plot(meanClickRates);
xlabel 'Generation #',ylabel 'Mean Click Rates';
title 'Mean Click Rates per Generation';
set(gca,'FontSize', 20);

[m,n] = size(population);
figure;
subplot(2,2,1),histogram(population(round(1),:),100,'Normalization','pdf');
xlabel 'Related post',ylabel(['Rate of appearance in Gen ' num2str(round(m/4))]);
subplot(2,2,2),histogram(population(round(2),:),100);
xlabel 'Related post',ylabel(['Rate of appearance in Gen ' num2str(round(m/2))]);
subplot(2,2,3),histogram(population(round(3),:),100);
xlabel 'Related post',ylabel(['Rate of appearance in Gen ' num2str(round(3*m/4))]);
subplot(2,2,4),histogram(population(round(m),:),100);
xlabel 'Related post',ylabel(['Rate of appearance in Gen ' num2str(round(m))]);


%%  Hist video
figure;
v = VideoWriter('hist_ps10_mr10_g100.mp4','MPEG-4');
v.FrameRate = 5;
% v.FileFormat = 'mp4';
[~,F] = mode(population,2);
open(v);
for i= 1:100
    x = -500:500;
    y = (0.9 -0.8.*abs(x)./500).^2;
    plot(x+500,y*max(F));
    hold on;
    h = histogram(population(round(i),:),100);
    xlabel 'Related post',ylabel(['Rate of appearance in Gen ' num2str(round(m))]);
    axis([0 1000 0 max(F)]);
    set(h,'edgecolor','none');
    set(h,'FaceAlpha',1);
    writeVideo(v,getframe(gcf));
    hold off;
end
close(v);
close all
%% pdf of posts
figure;
x = -500:500;
y = (0.9 -0.8.*abs(x)./500).^2;
plot(x+500,y);