clear;clc;close all;

data = csvread('out.csv');

meanClickRates = data(2:end,1);

population = data(:,2:end);

figure,plot(meanClickRates);
xlabel 'Generation #',ylabel 'Mean Click Rates';
title 'Mean Click Rates per Generation';
set(gca,'FontSize', 20);

[m,n] = size(population);
figure;
subplot(2,2,1),histogram(population(round(m/4),:),'probability');
xlabel 'Related post',ylabel(['Rate of appearance in Gen ' num2str(round(m/4))]);
subplot(2,2,2),histogram(population(round(m/2),:));
xlabel 'Related post',ylabel(['Rate of appearance in Gen ' num2str(round(m/2))]);
subplot(2,2,3),histogram(population(round(3*m/4),:));
xlabel 'Related post',ylabel(['Rate of appearance in Gen ' num2str(round(3*m/4))]);
subplot(2,2,4),histogram(population(round(m),:));
xlabel 'Related post',ylabel(['Rate of appearance in Gen ' num2str(round(m))]);
