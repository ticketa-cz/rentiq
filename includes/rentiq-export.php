<?php

define( "EXCEL_PLUGIN_DIR", RENTIQ_PATH . 'includes/plugins/phpexcel/' );

function rentiq_export_data( $query, $export_type ) {

	if ( file_exists( EXCEL_PLUGIN_DIR . '/PHPExcel.php' ) ) {
		
		//Include PHPExcel

		require_once ( EXCEL_PLUGIN_DIR . "/PHPExcel.php" );
		$objPHPExcel = new PHPExcel();

		// setup pdf //

		/*
		if (!PHPExcel_Settings::setPdfRenderer(
			PHPExcel_Settings::PDF_RENDERER_TCPDF,
			'tcpdf'
		)) {
			die(
				'NOTICE: Please set the $rendererName and $rendererLibraryPath values' .
				'<br />' .
				'at the top of this script as appropriate for your directory structure'
			);
		}
		*/
				
		// add header //

		$objPHPExcel->setActiveSheetIndex(0);
		$objPHPExcel->getActiveSheet()->setCellValue( 'A1', __( 'Date', 'rentiq' ));
		$objPHPExcel->getActiveSheet()->setCellValue( 'B1', __( 'Invoice type', 'rentiq' ));
		$objPHPExcel->getActiveSheet()->setCellValue( 'C1', __( 'Total', 'rentiq' ));
		$objPHPExcel->getActiveSheet()->setCellValue( 'D1', __( 'Reservation', 'rentiq' ));
		$objPHPExcel->getActiveSheet()->setCellValue( 'E1', __( 'Apartment', 'rentiq' ));
		$objPHPExcel->getActiveSheet()->setCellValue( 'F1', __( 'Parking', 'rentiq' ));
		$objPHPExcel->getActiveSheet()->setCellValue( 'G1', __( 'Owners', 'rentiq' ));
		$objPHPExcel->getActiveSheet()->setCellValue( 'H1', __( 'Payments', 'rentiq' ));
		$objPHPExcel->getActiveSheet()->setCellValue( 'I1', __( 'Balance', 'rentiq' ));
		
		$objPHPExcel->getActiveSheet()->getStyle('A1:I1')->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn('A:I')->setAutoSize(true);
		
		// add data //

		$i = 0;

		if ( $query->have_posts() ) {

			while ( $query->have_posts() ) {
	
				$query->the_post();
				global $post;
				$post_id = $post->ID;

				$post_type = get_post_type( $post_id );
				$post_type_obj = get_post_type_object( $post_type );
				$reservation_id = get_post_meta( $post_id, 'reservation_id', true );
				$apartment = get_the_terms( $reservation_id, 'apartment' )[0];
				$parking = get_the_terms( $reservation_id, 'parking' )[0];

				$owners = get_the_terms( $reservation_id, 'owner' );
				$owners_cell = '';
				foreach ( $owners as $owner ) {
					$owners_cell .= $owner->name . ', ';
				}

				$payments = get_post_meta( $post_id, 'invoice_payments', true );
				$payment_cell = '';
				if ( $payments ) {
					foreach ( $payments as $payment ) {

						$payment_date = get_post_meta( $payment, 'payment_date', true );
						$payment_type = get_the_terms( $payment, 'payment_types' )[0]->slug;
						$payment_amount = get_post_meta( $payment, 'payment_amount', true );

						$payment_cell .= Rentiq_class::rentiq_format_date( $payment_date ) . ' - ' . $payment_type . ' - ' . $payment_amount . __( '$', 'rentiq' ) . ' /// ';
					}
				}

				$balance = Rentiq_class::is_invoice_paid( $post_id );
				if ( $balance === true ) {
					$balance = 0;
				}
		
				$objPHPExcel->getActiveSheet()->setCellValue( 'A'.($i+2), get_the_date( 'd/m/Y', $post_id ) );
				$objPHPExcel->getActiveSheet()->setCellValue( 'B'.($i+2), $post_type_obj->labels->singular_name );
				$objPHPExcel->getActiveSheet()->setCellValue( 'C'.($i+2), get_post_meta( $post_id, 'invoice_total', true ) );
				$objPHPExcel->getActiveSheet()->setCellValue( 'D'.($i+2), $reservation_id );
				$objPHPExcel->getActiveSheet()->setCellValue( 'E'.($i+2), $apartment->name );
				$objPHPExcel->getActiveSheet()->setCellValue( 'F'.($i+2), $parking->name );
				$objPHPExcel->getActiveSheet()->setCellValue( 'G'.($i+2), $owners_cell );
				$objPHPExcel->getActiveSheet()->setCellValue( 'H'.($i+2), $payment_cell );
				$objPHPExcel->getActiveSheet()->setCellValue( 'I'.($i+2), $balance );

				$i++;
			}
		}

		foreach (range('A', 'I') as $column) {            
            $objPHPExcel->getActiveSheet()->getColumnDimension($column)->setAutoSize(true);
		}

		// Rename worksheet
		//$objPHPExcel->getActiveSheet()->setTitle('Simple');
		
		// Set active sheet index to the first sheet, so Excel opens this as the first sheet
		$objPHPExcel->setActiveSheetIndex(0);

		// filename and dir //

		$wp_upload_dir = wp_upload_dir();
		$save_dir = $wp_upload_dir['basedir'] . '/documents/incomes/';
		$file_name = 'income_export-' . date('d_m_Y-H_i.') . $export_type;
		$saved_file = $save_dir . $file_name;
		$saved_file_url = site_url( 'wp-content/uploads/documents/incomes/' . $file_name );
		
		// Redirect output to a client’s web browser

		ob_clean();
		ob_start();
		switch ( $export_type ) {
			case 'csv':
				// Redirect output to a client’s web browser (CSV)
				header("Content-type: text/csv");
				header("Cache-Control: no-store, no-cache");
				header('Content-Disposition: attachment; filename="export.csv"');
				$objWriter = new PHPExcel_Writer_CSV($objPHPExcel);
				$objWriter->setDelimiter(',');
				$objWriter->setEnclosure('"');
				$objWriter->setLineEnding("\r\n");
				//$objWriter->setUseBOM(true);
				$objWriter->setSheetIndex(0);
				$objWriter->save('php://output');
				break;
			case 'xls':
				// Redirect output to a client’s web browser (Excel5)
				header('Content-Type: application/octet-stream');
				header('Content-Disposition: attachment;filename='. $file_name);
				header('Cache-Control: max-age=0');
				$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
				$objWriter->save( $saved_file );
				return $saved_file_url;
				break;
			case 'pdf':
				header('Content-Type: application/pdf');
				header('Content-Disposition: attachment;filename='. $file_name);
				header('Cache-Control: max-age=0');
				$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'PDF');
				$objWriter->save( $saved_file );
				return $saved_file_url;
				break;	
			case 'xlsx':
				// Redirect output to a client’s web browser (Excel2007)
				header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
				header('Content-Disposition: attachment;filename="export.xlsx"');
				header('Cache-Control: max-age=0');
				$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
				$objWriter->save('php://output');
				break;
		}
		exit;
	}
}
