<?php

namespace Manager;

class Analytics
{
	protected $analytics;

	public function __construct($analytics)
	{
		$this->analytics = $analytics;
	}

	public function visits($from, $to, $amount = 1000)
	{

		// Create the ReportRequest object.
		$request = new Google_Service_AnalyticsReporting_ReportRequest();
		$request->setViewId('85851950');
		$request->setPageSize($amount);

		// Create the DateRange object.
		$dateRange = new Google_Service_AnalyticsReporting_DateRange();
		$dateRange->setStartDate(date('Y-m-d', $from));
		$dateRange->setEndDate(date('Y-m-d', $to));
		$request->setDateRanges($dateRange);

		// Create the Metrics object for visits
		$visits = new Google_Service_AnalyticsReporting_Metric();
		$visits->setExpression('ga:visits');
		$request->setMetrics([$visits]);

		// get the pagepath dimension
		$path = new Google_Service_AnalyticsReporting_Dimension();
		$path->setName('ga:pagePath');
		$request->setDimensions([$path]);

		// order by vists desc
		$order = new Google_Service_AnalyticsReporting_OrderBy();
		$order->setFieldName('ga:visits');
		$order->setSortOrder('DESCENDING');
		$request->setOrderBys([$order]);

		// filter on certain paths
		$filter = new Google_Service_AnalyticsReporting_DimensionFilter();
		$filter->setDimensionName('ga:pagePath');
		$filter->setOperator('REGEXP');
		$filter->setExpressions(['^/([a-z]+)?/?(boeken|auteurs|recensies|artikelen)/(.+)?']);
		$clause = new Google_Service_AnalyticsReporting_DimensionFilterClause();
		$clause->setFilters([$filter]);
		// $clause->setOperator('AND');
		$request->setDimensionFilterClauses([$clause]);


		$body = new Google_Service_AnalyticsReporting_GetReportsRequest();
		$body->setReportRequests([$request]);

		$reports = $this->analytics->reports->batchGet($body);

		$data = [];
		for ($i = 0; $i < count( $reports ); $i++ ) {
			$report = $reports[$i];
			$rows = $report->getData()->getRows();
			for ($j = 0; $j < count($rows) && $j < $amount; $j++) {
				$row = $rows[$j];
				$dimensions = $row->getDimensions();
				$metrics = $row->getMetrics();
				$data[$dimensions[0]] = $metrics[0]['values'][0];
			}
		}
		return $data;
	}


	public function event($cat, $event ,$from, $to)
	{
		// Create the DateRange object.
		$dateRange = new Google_Service_AnalyticsReporting_DateRange();
		$dateRange->setStartDate(date('Y-m-d', $from));
		$dateRange->setEndDate(date('Y-m-d', $to));

		// Create the Metrics object for total events
		$total = new Google_Service_AnalyticsReporting_Metric();
		$total->setExpression('ga:totalEvents');

		// get the label dimension
		$label = new Google_Service_AnalyticsReporting_Dimension();
		$label->setName('ga:eventLabel');

		// order by totalevents desc
		$order = new Google_Service_AnalyticsReporting_OrderBy();
		$order->setFieldName('ga:totalEvents');
		$order->setSortOrder('DESCENDING');

		// filter on category and action
		$category = new Google_Service_AnalyticsReporting_DimensionFilter();
		$category->setDimensionName('ga:eventCategory');
		$category->setOperator('EXACT');
		$category->setExpressions([$cat]);

		$action = new Google_Service_AnalyticsReporting_DimensionFilter();
		$action->setDimensionName('ga:eventAction');
		$action->setOperator('EXACT');
		$action->setExpressions([$event]);

		$clause = new Google_Service_AnalyticsReporting_DimensionFilterClause();
		$clause->setFilters([$category, $action]);
		$clause->setOperator('AND');

		// Create the ReportRequest object.
		$request = new Google_Service_AnalyticsReporting_ReportRequest();
		$request->setViewId('85851950');
		$request->setDateRanges($dateRange);
		$request->setMetrics([$total]);
		$request->setDimensions([$label]);
		$request->setDimensionFilterClauses([$clause]);
		$request->setOrderBys([$order]);

		$body = new Google_Service_AnalyticsReporting_GetReportsRequest();
		$body->setReportRequests([$request]);

		$reports = $this->analytics->reports->batchGet($body);


		$data = [];
		for ($i = 0; $i < count( $reports ); $i++ ) {
			$report = $reports[$i];
			$rows = $report->getData()->getRows();
			for ($j = 0; $j < count($rows) && $j < 25; $j++) {
				$row = $rows[$j];
				$dimensions = $row->getDimensions();
				$metrics = $row->getMetrics();
				return $metrics[0]['values'][0];
			}
		}
		return 0;
	}




	public function test()
	{


		// Create the DateRange object.
		$dateRange = new \Google_Service_AnalyticsReporting_DateRange();
		$dateRange->setStartDate(date('Y-m-d', time() - (5 * 24 * 3600) ));
		$dateRange->setEndDate(date('Y-m-d',  time() + 10000));

		// Create the Metrics object for total events
		$total = new \Google_Service_AnalyticsReporting_Metric();
		$total->setExpression('ga:totalEvents');

		// get the label dimension
		$label = new \Google_Service_AnalyticsReporting_Dimension();
		$label->setName('ga:eventLabel');

		// order by totalevents desc
		$order = new \Google_Service_AnalyticsReporting_OrderBy();
		$order->setFieldName('ga:totalEvents');
		$order->setSortOrder('DESCENDING');

		// filter on category and action
		$category = new \Google_Service_AnalyticsReporting_DimensionFilter();
		$category->setDimensionName('ga:eventCategory');
		$category->setOperator('EXACT');
		$category->setExpressions(['article']);


		$action = new \Google_Service_AnalyticsReporting_DimensionFilter();
		$action->setDimensionName('ga:eventAction');
		$action->setOperator('EXACT');
		$action->setExpressions(['click']);

		$clause = new \Google_Service_AnalyticsReporting_DimensionFilterClause();
		$clause->setFilters([$category, $action]);
		$clause->setOperator('AND');

		// Create the ReportRequest object.
		$request = new \Google_Service_AnalyticsReporting_ReportRequest();
		$request->setViewId('193747700');
		$request->setDateRanges($dateRange);
		$request->setMetrics([$total]);
		$request->setDimensions([$label]);
		$request->setDimensionFilterClauses([$clause]);
		$request->setOrderBys([$order]);

		$body = new \Google_Service_AnalyticsReporting_GetReportsRequest();
		$body->setReportRequests([$request]);

		$reports = $this->analytics->reports->batchGet($body);


		$data = [];
		for ($i = 0; $i < count( $reports ); $i++ ) {
			$report = $reports[$i];
			$rows = $report->getData()->getRows();
			for ($j = 0; $j < count($rows) && $j < 25; $j++) {
				$row = $rows[$j];
				$dimensions = $row->getDimensions();
				$metrics = $row->getMetrics();
				var_dump($row);
				//return $metrics[0]['values'][0];
			}
		}
		return 0;
	}
}