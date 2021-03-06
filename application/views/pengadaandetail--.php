<section class="pg-opt pin">
	<div class="container">
		<div class="row">
			<div class="col-md-6">
				<h2>Pengadaan</h2>
			</div>
			<div class="col-md-6">
				<ol class="breadcrumb">
					<li><a href="<?php echo base_url()?>">Beranda</a></li>
					<li>Pengadaan</li>
				</ol>
			</div>
		</div>
	</div>
</section>		

<section class="slice bg-3">
	<div class="w-section inverse blog-grid">
		<div class="container">
			<div class="row">
				<div class="col-md-8 mb-20">

					<?php

					$this->load->helper('engine');
					$this->load->helper('dateIndo');
					// echo "<pre>";
					// print_r($pengadaan);
					// echo "</pre>";
					foreach ($pengadaan as $detail) : ?>
					<div class="col-md-12 header-pengadaan-detail">
						<div class="row no-margin">
							<div class="col-md-12 col-xs-12">
								<?php  $lpse_id = substr($detail->lls_id,count($detail->lls_id)-4);?>
								<h1><?php echo $detail->pkt_nama?></h1>
							</div>
						</div>
						<!-- Pengumuman -->
						<div class="row no-margin"> 
							<div class="col-md-6 col-sm-6 col-xs-12">
								<label class="control-label">Pengumuman Lelang : <?php echo dateIndo($detail->dtj_tglawal); ?></label>
							</div>
							<div class="col-md-6 col-sm-6 col-xs-12 penawaran">
								<label class="control-label">Penawaran Terakhir : <?php echo dateIndo($detail->dtj_tglakhir); ?></label>
							</div>
						</div>
					</div>
					<div class="clearfix"></div>
				<!-- Tabs-->
					<div class="tabs">
						<ul class="nav nav-tabs">
							<li class="active"><a href="#one" data-toggle="tab"><i class="icon-star"></i> Keterangan Pengadaan</a></li>
							<li><a href="#two" data-toggle="tab">Syarat</a></li>
							<li><a href="#three" data-toggle="tab">Tahapan</a></li>
						</ul>
						<div class="tab-content no-border no-margin">
							<div class="tab-pane fade in active " id="one">
								<table class="table tb-lpse">
									<tbody>
										<tr>
										<td class="w-135">Kode Lelang</td><td>:</td><td colspan="2"><?php echo $detail->lls_id?></td></tr>
									<tr>
										<td>Unspsc<td>:</td><td colspan="2"></td></tr>
									<tr>
										<td>Nama lelang</td><td>:</td><td colspan="2"><?php echo $detail->pkt_nama?></td></tr>
									<tr>
										<td>Agensi</td><td>:</td><td colspan="2"><?php echo $detail->agc_nama?></td></tr>
									<tr>
										<td>Satuan</td><td>:</td><td colspan="2"><?php echo $detail->stk_nama?></td></tr>
									<tr>
										<td>Kategori Lelang</td><td>:</td><td colspan="2"><?php echo kategori($detail->kgr_id)?></td></tr>
									<tr>
										<td>Metode Pengadaan</td><td>:</td><td colspan="2"><?php echo metodePemilihan($detail->mtd_pemilihan)?></td></tr>
									<tr>
										<td>Metode Kualifikasi</td><td>:</td><td colspan="2"><?php echo metodeKualifikasi($detail->mtd_id)?></td></tr>
									<tr>
										<td>Metode Evaluasi</td><td>:</td><td colspan="2"><?php echo metodeEvaluasi($detail->mtd_evaluasi)?></td></tr>
									<tr>
										<td>Tahun</td><td>:</td><td colspan="2"> - </td></tr>
									<tr>
										<td>Pagu</td><td>:</td><td colspan="2">Rp. <?php echo number_format($detail->pkt_pagu);?></td></tr>
									<tr>
										<td>HPS</td><td>:</td><td colspan="2">Rp. <?php echo number_format($detail->pkt_hps);?></td></tr>
									<tr>
										<td>Lokasi</td><td>:</td><td colspan="2">Jakarta Utara - Jakarta Utara (Kota)</td></tr>
									<tr>
										<td>Kontrak</td><td>:</td>

									<?php 
									$carabayar = $tahunAnggaran = $sumberPendanaan = '-';
									foreach ($kontrak as $dkontrak) {

										$carabayar = jenisKontrak($dkontrak->lls_kontrak_pembayaran);
										$tahunAnggaran = jenisKontrak($dkontrak->lls_kontrak_tahun);
										$sumberPendanaan = jenisKontrak($dkontrak->lls_kontrak_sbd);

									} ?>
										<td>Cara Pembayaran</td><td><?php echo $carabayar?></td></tr>
									<tr><td  class="no-border" rowspan="2" colspan="2">&nbsp;</td><td>Pembebanan Tahun Anggaran</td><td><?php echo $tahunAnggaran;?></td></tr>
									<tr><td>Sumber Pendanaan</td><td><?php echo $sumberPendanaan?></td></tr>
									<tr>
										<td>Kualifikasi</td><td>:</td><td colspan="2"><?php echo kualifikasi($detail->kls_id)?></td></tr>
									<tr>
										<td>Sumberdana</td><td>:</td><td colspan="2"><a href="#">APBD</a></td></tr>
									<tr>
										<td>Website</td><td>:</td><td colspan="2"><a href="<?php echo prep_url($detail->agc_website) ?>"><?php echo prep_url($detail->agc_website)?></a></td></tr>
									</tbody>
								</table>
							</div>
							<div class="tab-pane fade" id="two">
								<!-- Belum terintegrasi -->
								<table class="table tb-lpse">
									<tbody>


										<?php $dataSyarat =  getSyarat($detail->lls_id); 
										if(count($dataSyarat) > 0) {
										foreach ($dataSyarat as $valueSyarat) :
										?>

										<tr>
											<td><?php echo $valueSyarat->ckm_nama?> : <?php echo $valueSyarat->chk_nama?></td></tr>
										<?php endforeach;
										}else{
											echo "Data belum tersedia";
										}
										?>	
									</tbody>
								</table>
							</div>
							<div class="tab-pane fade" id="three">
								<table class="table text-center">
									<tbody>
										<tr>
											<th class="text-center">Tahap</th><th class="text-center">Mulai</th><th class="text-center">Sampai</th><th class="text-center">History Perubahan</th></tr>
										
										<?php
								
										foreach ($tahap as $dtahap) :
									
										?>
										<tr>
											<td><?php echo tahap($dtahap->thp_id)?></td><td><?php echo dateIndo($dtahap->dtj_tglawal)?> <?php echo '';//date('H:i',strtotime($dtahap->dtj_tglawal))?></td><td><?php echo dateIndo($dtahap->dtj_tglakhir)?> <?php echo '';// date('H:i',strtotime($dtahap->dtj_tglakhir))?></td><td>Tidak ada</td></tr>
										<?php endforeach;?>
									</tbody>
								</table>
							</div>
						</div>							
					</div>
					<?php endforeach?>
				</div>
				
				<div class="col-md-4 section-right">
					<div class="server-lpse">
						<div class="row">
							<div class="col-md-3 col-xs-12">
								
								
								<img src="<?php echo base_url()?>images/no-image.png" class="img-responsive img-center">
							</div>
							<div class="col-md-9 col-xs-12">
								<a href="<?php echo base_url()?>lpse/detail/0/0/0/<?php echo $lpse_id?>/" class="link-server"><?php echo word_limiter(lpseName($lpse_id),3)?></a>
							</div>
						</div>
						
						<ul>
							<?php 
							$lpseUrl  = lpseUrl($lpse_id);
							if (filter_var($lpseUrl , FILTER_VALIDATE_URL) === FALSE) {
								   
									
									echo '<li><label class="point-server" style="background:red;">&nbsp;</label>Server Offline</li>';
								}else{
									 ini_set("default_socket_timeout","05");
							         set_time_limit(5);
							         $f=fopen($lpseUrl ,"r");
							         $r=fread($f,1000);
							         fclose($f);
							         if(strlen($r)>1) {
							        
							         echo '<li><label class="point-server" style="background:#16D315;">&nbsp;</label>Server Online</li>';
							         }
							         else {
							           echo '<li><label class="point-server" style="background:red;">&nbsp;</label>Server Offline</li>';
							         }	
								}

							?>
							
							<li>24 menit yang lalu</li>
							<li><a href="#">History status Server&raquo;</a></li>
							<li>Cek status server tiap 60 menit</li>
						</ul>
						
						<!-- <h5>Total 1023 Pengadaan</h5>
						<h5>Aktif 4 Pengadaan</h5> -->
					</div>

					<?php

					// echo "<pre>";
					// print_r($syarat);
					// echo "</pre>";

					?>
					
					<h3 class="section-title-2 mb-0 no-border">Peta Lokasi LPSE</h3> 
					<div class="peta-lpse">
						<?php

							$map = lpseMap($lpse_id);

						?>
						<iframe width="360" height="280" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="https://maps.google.com/maps?q=<?php echo  $map['lat']?>,<?php echo  $map['long']?>&hl=es;z=14&amp;output=embed"></iframe><br /><small><a href="https://maps.google.com/maps?q=<?php echo  $map['lat']?>,<?php echo  $map['long']?>&hl=es;z=14&amp;output=embed" style="color:#0000FF;text-align:left" target="_blank">See map bigger</a></small>
		
					</div>
					<h3 class="section-title-2">Pengadaan Terbaru</h3>
					<ul id="pengadaan-baru">

						<?php foreach ($pengadaanTerbaru as $detailTerbaru) : ?>
							


						<li>
							<a href="<?php echo base_url()?>pengadaan/detail/<?php echo $detailTerbaru->lls_id?>/<?php echo $detailTerbaru->pkt_id?>"><?php echo $detailTerbaru->pkt_nama?></a>
							<label>Pagu: Rp. <?php echo number_format($detailTerbaru->pkt_pagu);?></label>
						</li>
						<?php endforeach;?>
					</ul>
				</div>
			</div>
		</div>
	</div>
</section>