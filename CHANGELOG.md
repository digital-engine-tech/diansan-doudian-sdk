# Changelog

## [1.1.0] - 2026-11-27

### Added
- 英文文档 `README.en.md`，与中文 `README.md` 顶部互链切换

### Removed
- `examples/` 演示脚本与 `.env.example`
- `DouDianFactory::fromEnv()` / `Config::fromEnv()`
- 开发依赖 `vlucas/phpdotenv`

### Compatibility
- PHP **7.4 (min)** – **8.x (max, `< 9.0`)**

## [1.0.0] - 2026-11-26

### Added
- Package: `digital-engine-tech/diansan-doudian-sdk`
- Factory: `DianSan\DouDianFactory`
- Client facade: `DianSan\DouDian`
- Product publish / edit / async batch shelf APIs
- `DianSan\Support\ApiResult`
- 中文主文档：`README.md`

### Compatibility
- PHP **7.4 (min)** – **8.x (max, `< 9.0`)**
