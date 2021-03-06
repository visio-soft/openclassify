<?php

use Anomaly\ConfigurationModule\Configuration\Contract\ConfigurationRepositoryInterface;
use Anomaly\DashboardModule\Dashboard\Contract\DashboardRepositoryInterface;
use Anomaly\DashboardModule\Widget\Contract\WidgetRepositoryInterface;
use Illuminate\Database\Seeder;

class widgetSeeder extends Seeder
{
    protected $widgets;
    protected $dashboards;
    protected $configuration;
    public function __construct(
        WidgetRepositoryInterface $widgets,
        DashboardRepositoryInterface $dashboards,
        ConfigurationRepositoryInterface $configuration
    ) {
        $this->widgets       = $widgets;
        $this->dashboards    = $dashboards;
        $this->configuration = $configuration;
    }
    public function run()
    {
        $this->widgets->truncate();

        $dashboard = $this->dashboards->findBySlug('welcome');

        $widget = $this->widgets
            ->create(
                [
                    'en'        => [
                        'title'       => 'ARecent News',
                        'description' => 'ARecent news from http://pyrocms.com/',
                    ],
                    'extension' => 'anomaly.extension.xml_feed_widget',
                    'dashboard' => $dashboard,
                ]
            );

        $this->configuration->purge('anomaly.extension.xml_feed_widget');

        $this->configuration->create(
            [
                'scope' => $widget->getId(),
                'key'   => 'anomaly.extension.xml_feed_widget::url',
                'value' => 'http://www.pyrocms.com/posts/rss.xml',
            ]
        );
    }
}
