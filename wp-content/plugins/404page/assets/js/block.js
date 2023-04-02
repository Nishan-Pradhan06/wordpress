wp.blocks.registerBlockType( 'petersplugins/the-url', {
	
	title: wp.i18n.__( 'URL causing 404 error', '404page' ),
	icon: 'editor-unlink',
	category: 'widgets',
	attributes:  {
		urltype: {
			type: 'string',
			default: 'full'
		},
		alignment: {
            type: 'string',
            default: 'none'
        }
	},
	supports: {
		color: true
	},
	
	edit( props ) {
		
		const attributes =  props.attributes;
		const setAttributes =  props.setAttributes;
		const blockProps = wp.blockEditor.useBlockProps();

		const alignmentClass = (attributes.alignment != null) ? 'has-text-align-' + attributes.alignment : '';
		
		function changeType( newUrltype ) {
			setAttributes( { urltype: newUrltype } );
		}
		
		function changeAlignment( newAlignment ) {
            setAttributes( {
                alignment: newAlignment === undefined ? 'none' : newAlignment,
            } );
        };

		return wp.element.createElement('div', { className: alignmentClass }, [
		
			wp.element.createElement( 'p', blockProps, wp.i18n.__( 'The URL that is causing the 404 error is displayed here', '404page' ) ),
			
			wp.element.createElement( wp.blockEditor.InspectorControls, {}, [		
				
				wp.element.createElement( wp.components.PanelBody, {
				
					title: wp.i18n.__( 'Display', '404page' ),
					initialOpen: true 

				}, [
						
					wp.element.createElement( wp.components.SelectControl, {
						value: attributes.urltype,
						label: wp.i18n.__( 'Type', '404page' ),
						onChange: changeType,
						options: [
							{value: 'page', label: wp.i18n.__( 'Page', '404page') },
							{value: 'domainpath', label: wp.i18n.__( 'Domain Path', '404page' ) },
							{value: 'full', label: wp.i18n.__( 'Full', '404page' ) },
						]
					} )
					
				] ) 
			
			] ),
			
			wp.element.createElement( wp.blockEditor.BlockControls, {}, [
				
				wp.element.createElement( wp.blockEditor.AlignmentToolbar, {
					value: attributes.alignment,
                    onChange: changeAlignment
				} )
			
			] )
		] )
	},
	
	save() {
		
		const blockProps = wp.blockEditor.useBlockProps.save();
		
		return null;
	}
	
});