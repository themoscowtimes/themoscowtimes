<?php view::extend('template/default'); ?>

<?php view::start('billboard') ?>
	<?php view::banner('event_top') ?>
<?php view::end() ?>

<?php view::start('main') ?>

<div class="container">

	<div class="row-flex">
		<div class="col" >
			<div class="item-header">
				<h3 class="header--style-3"><?php view::lang('Events') ?></h3>
			</div>
		</div>
	</div>

	<div class="row-flex" y-use="Events" data-items="<?php view::attr($json); ?>">


		<script type="text/html" y-name="event">
			<?php $bem = fetch::bem('event-excerpt-horizontal') ?>
			<div class="excerpt-horizontal <?php view::attr($bem()) ?> mb-3">
				<div class="row-flex">
					<div class="col-auto">
						<figure class="imagewrap-square-120 <?php view::attr($bem('image')); ?>">
							{% if image %}
								<img class="" src="{{ image }}" />
							{% endif %}
						</figure>
					</div>
					<div class="col">
						<div class="<?php view::attr($bem('content')) ?>">
							{% if places.length > 0 %}
								{% each places as place %}
									<div class="label label--type-2 label--color-8">
										{{ place.title }}
									</div>
								{% endeach %}
							{% endif %}

							<h3 class="header--style-4 <?php view::attr($bem('headline')) ?>">
								{{ title }}
								<?php /* <span class=" label label--color-4 <?php view::attr($bem('headline')) ?>__label"><?php view::raw($item->type) ?></span> */ ?>
							</h3>
							{% if min_age %}
								<span class="label label--type-2 label--color-6">
									{{ min_age }}
								</span>
							{% endif %}

							{% if booking_link %}
								<a href="{{ booking_link }}">Book tickets</a>
							{% endif %}


							{%
								var excerpt = description;
								var more = false;
								if(excerpt.length > 100) {
									excerpt = excerpt.substr(0,100) + '...';
									more = true;
								}
							%}

							<div y-name="excerpt">
								{{{ excerpt }}}
							</div>

							{% if more || places.length > 0 || dates.length > 0 %}
								<div y-name="more" style="display:none;">
									{{{ description }}}
									<br />
									{% each places as place %}
										<h3 class="header--style-4 <?php view::attr($bem('headline')) ?> mt-3">
											{{ place.title }}
										</h3>
										<div>
										 {{ place.description }}
										</div>
									{% endeach %}

									<?php /*
									{%  if dates.length > 0 %}
										<select>
											<option>Dates and times</option>
											{% each dates as date %}
												{%
												var start = new Date(date.start).toLocaleString({month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit' });
												var end = new Date(date.end).toLocaleString({hour: '2-digit', minute: '2-digit'});
												%}

												<option>
													{{ start }} - {{ end }}
												</option>

											{% endeach %}
										</select>
									{% endif  %}
									 */ ?>
								</div>
							{% endif %}

						</div>
					</div>
				</div>
				</a>
			</div>
		</script>


		<div class="col" >
			<div class="row-flex">
				<div class="col-auto">
					<div class="list-filter" >
						<div>
							<?php view::asset('js', fetch::url('base').'vendor/moment/Moment.js'); ?>
							<div class="list__section">
								<h4><?php view::lang('When'); ?></h4>
								<select y-name="day">
									<option value=""><?php view::lang('Pick a day') ?></option>
									<option value="today"><?php view::lang('Today') ?></option>
									<option value="tomorrow"><?php view::lang('Tomorrow') ?></option>
									<option value="weekend"><?php view::lang('This weekend') ?></option>
									<option value="week"><?php view::lang('This week') ?></option>
									<option value="nextweek"><?php view::lang('Next week') ?></option>
									<option value="month"><?php view::lang('This month') ?></option>
									<option value="nextmonth"><?php view::lang('Next month') ?></option>
								</select>
							</div>

							<div class="list__section">
								<h4><?php view::lang('Or a specific date') ?></h4>
								<div
									y-use="Date"
									y-name="date"
									data-value="<?php view::attr(date('Y-m-d H:i:s')) ?>"
									data-time="false"
									class="datepicker"
								>
									<div  y-name="container"></div>
									<script type="text/html" y-name="calendar">
										<div>
											<div class="row-fluid">
												<div class="col-2">
													<span class="fa fa-angle-left clickable" y-on="click|stop:previous"></span>
												</div>
												<div class="col-8">
													<h4>{{ month }} {{ year }}</h4>
												</div>
												<div class="col-2">
													<span class="fa fa-angle-right clickable" y-on="click|stop:next"></span>
												</div>
											</div>
											<table class="datepicker__table">
												<thead>
													<tr>
														{% each days as day %}
															<th>{{ day }}</th>
														{% endeach %}
													</tr>
												</thead>
												<tbody>
													{% each weeks as week %}
														<tr>
															{% each week as day %}
																{% if day %}
																	<td class="text-center"  y-on="click|stop:date({{ year }}, {{ monthnumber }}, {{ day }})">
																		<span y-name="day day-{{ day }}" class="{% if active == day %}active{% else %}non-active{% endif %} clickable">
																			{{ day }}
																		</span>
																	</td>
																{% else %}
																	<td>&nbsp;</td>
																{% endif %}
															{% endeach %}
														</tr>
													{% endeach %}
												</tbody>
											</table>
											{% if time %}
												<div class="row">
													<div class="col-2"></div>
													<div class="col-8">
														<table class="table table-borderless table-sm">
															<tr>
																<td  class="text-center"><i class="icon clickable" y-on="click|stop:hourup">keyboard_arrow_up</i></td>
																<td  class="text-center"><i class="icon clickable" y-on="click|stop:minuteup">keyboard_arrow_up</i></td>
															</tr>
															<tr>
																<td  class="text-center"><input type="text" class="form-control form-control-lg text-center" y-on="change:hourchange" y-name="hour" value="{{ current.hour }}" /></td>
																<td  class="text-center"><input type="text" class="form-control form-control-lg text-center" y-on="change:minutechange" y-name="minute" value="{{ current.minute }}" /></td>
															</tr>
															<tr>
																<td  class="text-center"><i class="icon clickable" y-on="click|stop:hourdown">keyboard_arrow_down</i></td>
																<td  class="text-center"><i class="icon clickable" y-on="click|stop:minutedown">keyboard_arrow_down</i></td>
															</tr>
														</table>
													</div>
												</div>
											{% endif %}
										</div>
									</script>
								</div>
							</div>
							<?php /*
							<div class="list__section">
								<h4><?php view::lang('to'); ?></h4>
								<select y-name="type">
									<option value="">Event type</option>
									<option value="theater">Theater</option>
									<option value="movie">Movie</option>
									<option value="concert">Concert</option>
								</select>
							</div>
							 */ ?>
						</div>
					</div>
				</div>
				<div class="col" y-name="events">

					<?php /* foreach ($items as $item): ?>
						<div class="mb-3">
							<div y-name="event" data-date="<?php view::attr(strtotime($item->time)) ?>" data-type="<?php view::attr($item->type) ?>">
								<?php view::file('event/excerpt/horizontal', ['item' => $item]); ?>
							</div>
						</div>
					<?php endforeach; */ ?>

				</div>
			</div>
		</div>

		<div class="col-auto hidden-md-down">
			<aside class="sidebar" style="">

				<section class="sidebar__section">
					<div class="sidebar__section__header">
						<h3 class="sidebar__section__label header--style-3 "><?php view::lang('Arts & lifestyle'); ?></h3>
					</div>
					<?php foreach ($city as $article): ?>
						<div class="mb-2">
							<a href="<?php view::route('article', ['slug'=>$article->slug]); ?>" title="<?php view::attr($article['title']) ?>">
								<div class="label color-3"><?php view::text($article['section']) ?></div>
								<h5 class="header--style-5">
									<?php view::text($article['title']) ?>
								</h5>
							</a>
						</div>
					<?php endforeach; ?>
				</section>
			</aside>
		</div>
	</div>
</div>
<?php view::end(); ?>