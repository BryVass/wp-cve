module.exports = {
	plugins: [
		require("autoprefixer"),
		require("postcss-preset-env"),
		require("postcss-nested"),
		require("postcss-import"),
		require("cssnano")({
			preset: "default",
		}),
	],
};
