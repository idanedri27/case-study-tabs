<?php
/**
 * Case Study Tabs — ACF block render template.
 *
 * @package case-study-theme
 *
 * @var array  $block      Block settings and attributes.
 * @var bool   $is_preview True during backend preview render.
 * @var int    $post_id    Post ID the block is rendered on.
 */

$section_title   = get_field( 'section_title' );
$title_highlight = get_field( 'section_title_highlight' );
$tabs            = get_field( 'tabs' );

$block_id = 'cst-tabs-' . $block['id'];
if ( ! empty( $block['anchor'] ) ) {
	$block_id = $block['anchor'];
}

if ( ! $tabs ) {
	if ( $is_preview ) {
		echo '<p style="padding:24px;background:#07102F;color:#fff;border-radius:8px;">Case Study Tabs — add at least one tab in the block settings.</p>';
	}
	return;
}
?>
<section id="<?php echo esc_attr( $block_id ); ?>" class="cst-tabs" data-cst-tabs>
	<div class="cst-tabs__inner">

		<?php if ( $section_title || $title_highlight ) : ?>
			<h2 class="cst-tabs__title">
				<?php echo esc_html( $section_title ); ?>
				<?php if ( $title_highlight ) : ?>
					<span class="cst-tabs__title-highlight">
						<?php echo esc_html( $title_highlight ); ?>
						<svg class="cst-tabs__title-underline" viewBox="0 0 240 22" fill="none" aria-hidden="true" preserveAspectRatio="none">
							<path d="M4 14C60 8 150 5 236 8" stroke="url(#cst-underline-grad-<?php echo esc_attr( $block['id'] ); ?>)" stroke-width="5" stroke-linecap="round"/>
							<path d="M14 19C80 14 160 12 224 15" stroke="url(#cst-underline-grad-<?php echo esc_attr( $block['id'] ); ?>)" stroke-width="4" stroke-linecap="round"/>
							<defs>
								<linearGradient id="cst-underline-grad-<?php echo esc_attr( $block['id'] ); ?>" x1="0" y1="11" x2="240" y2="11" gradientUnits="userSpaceOnUse">
									<stop stop-color="#68F2D7"/>
									<stop offset="1" stop-color="#6DDFEB"/>
								</linearGradient>
							</defs>
						</svg>
					</span>
				<?php endif; ?>
			</h2>
		<?php endif; ?>

		<div class="cst-tabs__tabbar-wrap">
			<div class="cst-tabs__tabbar" role="tablist" aria-label="<?php esc_attr_e( 'Case studies', 'case-study-theme' ); ?>" data-cst-tablist>
				<?php foreach ( $tabs as $i => $tab ) : ?>
					<button
						class="cst-tabs__tab<?php echo 0 === $i ? ' is-active' : ''; ?>"
						id="<?php echo esc_attr( $block_id . '-tab-' . $i ); ?>"
						type="button"
						role="tab"
						aria-selected="<?php echo 0 === $i ? 'true' : 'false'; ?>"
						aria-controls="<?php echo esc_attr( $block_id . '-panel-' . $i ); ?>"
						<?php echo 0 === $i ? '' : 'tabindex="-1"'; ?>
						data-cst-tab="<?php echo esc_attr( $i ); ?>"
					>
						<?php if ( ! empty( $tab['tab_logo'] ) ) : ?>
							<img
								class="cst-tabs__tab-logo"
								src="<?php echo esc_url( $tab['tab_logo']['url'] ); ?>"
								alt="<?php echo esc_attr( $tab['tab_name'] ); ?>"
								loading="lazy"
							>
						<?php else : ?>
							<span class="cst-tabs__tab-name"><?php echo esc_html( $tab['tab_name'] ); ?></span>
						<?php endif; ?>
					</button>
				<?php endforeach; ?>
			</div>
		</div>

		<div class="cst-tabs__panels" data-cst-panels>
			<?php
			foreach ( $tabs as $i => $tab ) :
				$quote_logo = ! empty( $tab['quote_logo'] ) ? $tab['quote_logo'] : $tab['tab_logo'];
				$link       = $tab['case_study_link'];
				$solutions  = $tab['solutions'];
				$stats      = $tab['stats'];
				$video      = $tab['video'];
				$poster     = $tab['video_poster'];
				?>
				<div
					class="cst-tabs__panel<?php echo 0 === $i ? ' is-active' : ''; ?>"
					id="<?php echo esc_attr( $block_id . '-panel-' . $i ); ?>"
					role="tabpanel"
					aria-labelledby="<?php echo esc_attr( $block_id . '-tab-' . $i ); ?>"
					<?php echo 0 === $i ? '' : 'hidden'; ?>
					data-cst-panel="<?php echo esc_attr( $i ); ?>"
				>
					<div class="cst-tabs__card">

						<div class="cst-tabs__quote-col">
							<span class="cst-tabs__quote-mark" aria-hidden="true">
								<svg viewBox="0 0 78 66" fill="none" aria-hidden="true">
									<path d="M0 66V40.9C0 33.1 1.4 25.9 4.2 19.4C7.1 12.8 11.9 6.3 18.6 0L32.3 9.4C28.4 13.5 25.5 17.4 23.6 21.2C21.8 24.9 20.8 28.8 20.6 32.9H33.4V66H0ZM44.6 66V40.9C44.6 33.1 46 25.9 48.8 19.4C51.7 12.8 56.5 6.3 63.2 0L76.9 9.4C73 13.5 70.1 17.4 68.2 21.2C66.4 24.9 65.4 28.8 65.2 32.9H78V66H44.6Z" fill="#68F2D7"/>
								</svg>
							</span>

							<blockquote class="cst-tabs__quote">
								<p><?php echo esc_html( $tab['quote'] ); ?></p>
							</blockquote>

							<div class="cst-tabs__author">
								<?php if ( ! empty( $quote_logo ) ) : ?>
									<img
										class="cst-tabs__author-logo"
										src="<?php echo esc_url( $quote_logo['url'] ); ?>"
										alt="<?php echo esc_attr( $tab['tab_name'] ); ?>"
										loading="lazy"
									>
								<?php endif; ?>
								<div class="cst-tabs__author-meta">
									<?php if ( ! empty( $tab['author_name'] ) ) : ?>
										<span class="cst-tabs__author-name"><?php echo esc_html( $tab['author_name'] ); ?></span>
									<?php endif; ?>
									<?php if ( ! empty( $tab['author_role'] ) ) : ?>
										<span class="cst-tabs__author-role"><?php echo esc_html( $tab['author_role'] ); ?></span>
									<?php endif; ?>
								</div>
							</div>

							<?php if ( ! empty( $link['url'] ) ) : ?>
								<a
									class="cst-tabs__cta"
									href="<?php echo esc_url( $link['url'] ); ?>"
									<?php echo ! empty( $link['target'] ) ? 'target="' . esc_attr( $link['target'] ) . '" rel="noopener"' : ''; ?>
								>
									<span><?php echo esc_html( $link['title'] ? $link['title'] : __( 'Case Study', 'case-study-theme' ) ); ?></span>
									<span class="cst-tabs__cta-arrow" aria-hidden="true">
										<svg viewBox="0 0 16 16" fill="none"><path d="M3 8H13M13 8L9 4M13 8L9 12" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
									</span>
								</a>
							<?php endif; ?>
						</div>

						<div class="cst-tabs__data-col">

							<?php if ( $solutions ) : ?>
								<div class="cst-tabs__data-card cst-tabs__data-card--solutions">
									<span class="cst-tabs__data-label"><?php echo esc_html( $tab['solutions_label'] ); ?></span>
									<ul class="cst-tabs__solutions">
										<?php foreach ( $solutions as $solution ) : ?>
											<li class="cst-tabs__solution">
												<?php if ( ! empty( $solution['icon'] ) ) : ?>
													<img src="<?php echo esc_url( $solution['icon']['url'] ); ?>" alt="" loading="lazy">
												<?php endif; ?>
												<span><?php echo esc_html( $solution['label'] ); ?></span>
											</li>
										<?php endforeach; ?>
									</ul>
								</div>
							<?php endif; ?>

							<?php if ( ! empty( $tab['crm_name'] ) || ! empty( $tab['crm_icon'] ) ) : ?>
								<div class="cst-tabs__data-card cst-tabs__data-card--crm">
									<span class="cst-tabs__data-label"><?php echo esc_html( $tab['crm_label'] ); ?></span>
									<div class="cst-tabs__crm">
										<?php if ( ! empty( $tab['crm_icon'] ) ) : ?>
											<img src="<?php echo esc_url( $tab['crm_icon']['url'] ); ?>" alt="" loading="lazy">
										<?php endif; ?>
										<span><?php echo esc_html( $tab['crm_name'] ); ?></span>
									</div>
								</div>
							<?php endif; ?>

							<?php if ( $stats || ! empty( $tab['crm_name'] ) ) : ?>
								<div class="cst-tabs__stats">
									<?php if ( ! empty( $tab['crm_name'] ) || ! empty( $tab['crm_icon'] ) ) : ?>
										<div class="cst-tabs__mobile-crm">
											<?php if ( ! empty( $tab['crm_icon'] ) ) : ?>
												<img src="<?php echo esc_url( $tab['crm_icon']['url'] ); ?>" alt="" loading="lazy">
											<?php endif; ?>
											<strong><?php echo esc_html( $tab['crm_name'] ); ?></strong>
											<span><?php echo esc_html( $tab['crm_label'] ); ?></span>
										</div>
									<?php endif; ?>
									<?php foreach ( $stats as $stat ) : ?>
										<div class="cst-tabs__stat">
											<span class="cst-tabs__stat-value"><?php echo esc_html( $stat['value'] ); ?></span>
											<span class="cst-tabs__stat-label"><?php echo esc_html( $stat['label'] ); ?></span>
										</div>
									<?php endforeach; ?>
								</div>
							<?php endif; ?>

						</div>

						<div class="cst-tabs__video-col">
							<figure class="cst-tabs__video-card" data-cst-video>
								<?php if ( ! empty( $tab['video_brands'] ) ) : ?>
									<div class="cst-tabs__video-brands">
										<img src="<?php echo esc_url( $tab['video_brands']['url'] ); ?>" alt="" loading="lazy">
									</div>
								<?php endif; ?>

								<?php if ( ! empty( $video['url'] ) ) : ?>
									<video
										class="cst-tabs__video"
										preload="metadata"
										playsinline
										<?php echo ! empty( $poster['url'] ) ? 'poster="' . esc_url( $poster['url'] ) . '"' : ''; ?>
									>
										<source src="<?php echo esc_url( $video['url'] ); ?>" type="<?php echo esc_attr( $video['mime_type'] ); ?>">
									</video>
									<button class="cst-tabs__video-play" type="button" aria-label="<?php esc_attr_e( 'Play video', 'case-study-theme' ); ?>" data-cst-play>
										<svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M8 5.5V18.5L19 12L8 5.5Z" fill="currentColor"/></svg>
									</button>
								<?php elseif ( ! empty( $poster['url'] ) ) : ?>
									<img class="cst-tabs__video" src="<?php echo esc_url( $poster['url'] ); ?>" alt="" loading="lazy">
								<?php endif; ?>

								<?php if ( ! empty( $tab['video_person_name'] ) ) : ?>
									<figcaption class="cst-tabs__video-person">
										<span class="cst-tabs__video-person-name"><?php echo esc_html( $tab['video_person_name'] ); ?></span>
										<?php if ( ! empty( $tab['video_person_role'] ) ) : ?>
											<span class="cst-tabs__video-person-role"><?php echo esc_html( $tab['video_person_role'] ); ?></span>
										<?php endif; ?>
									</figcaption>
								<?php endif; ?>
							</figure>
						</div>

					</div>
				</div>
			<?php endforeach; ?>
		</div>

	</div>
</section>
