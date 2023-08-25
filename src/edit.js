/**
 * Retrieves the translation of text.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/packages/packages-i18n/
 */
import { __ } from '@wordpress/i18n';

/**
 * React hook that is used to mark the block wrapper element.
 * It provides all the necessary props like the class name.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/packages/packages-block-editor/#useblockprops
 */
import { useBlockProps, InspectorControls, RichText } from '@wordpress/block-editor';
import { InnerBlocks } from '@wordpress/block-editor';
import { useState } from '@wordpress/element';
import {
	PanelBody,
	SelectControl,
  TextControl,
} from '@wordpress/components';
/**
 * Lets webpack process CSS, SASS or SCSS files referenced in JavaScript files.
 * Those files can contain any CSS code that gets applied to the editor.
 *
 * @see https://www.npmjs.com/package/@wordpress/scripts#using-css
 */
import './editor.scss';

/**
 * The edit function describes the structure of your block in the context of the
 * editor. This represents what the editor will render when the block is used.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/block-api/block-edit-save/#edit
 *
 * @return {WPElement} Element to render.
 */
export default function Edit( { attributes, setAttributes } ) {
	const blockProps = useBlockProps();
	const shouldDisplayBlockA = true;
	const [ live, isLive ] = useState( 'yes' );

	return (
		<div { ...blockProps }>
			<InspectorControls>
			<PanelBody title={ __( 'Block settings', 'twitchcraft' ) }>
				<SelectControl
					label="Stream Status"
					value={ live }
					options={ [
						{ label: 'Live on Twitch', value: 'yes' },
						{ label: 'Not Live', value: 'no' },
					] }
					onChange={ ( live ) => isLive( live ) }
					__nextHasNoMarginBottom
				/>
        <TextControl
            label="Username"
            value={ attributes.userName }
            onChange={ ( userName ) => setAttributes( { userName } ) }
        />
			</PanelBody>
		</InspectorControls>
        { live === 'yes' ?  ( 
          <div>
            <h3>Online</h3>
            <RichText
                { ...blockProps }
                value={ attributes.liveContent } // Any existing content, either from the database or an attribute default
                onChange={ ( liveContent ) => setAttributes( { liveContent } ) } // Store updated content as a block attribute
                placeholder={ __( 'Add the content to display here.' ) } // Display this text before any content has been added by the user
            />
          </div>
        ): (
          <div>
            <h3>Offline</h3>
            <RichText
              { ...blockProps }
              value={ attributes.offlineContent } // Any existing content, either from the database or an attribute default
              onChange={ ( offlineContent ) => setAttributes( { offlineContent } ) } // Store updated content as a block attribute
              placeholder={ __( 'Add the content to display here.' ) } // Display this text before any content has been added by the user
            />
        </div>
          )}
      </div> 
	);
}
 