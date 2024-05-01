GEN_DIR := $(shell git rev-parse --show-toplevel)
PROTOC_PHP_PLUGIN_PATH := $(shell which grpc_php_plugin)
PROTO_SRC_DIR := $(GEN_DIR)/milvus-proto/proto
PROTO_DEST_DIR := $(GEN_DIR)/Milvus


protos-generate:
	$(foreach file, \
		$(wildcard $(PROTO_SRC_DIR)/*), \
		$(shell protoc \
			--proto_path=$(PROTO_SRC_DIR) \
			--php_out=$(GEN_DIR) \
			--grpc_out=$(GEN_DIR) \
			--plugin=protoc-gen-grpc=$(PROTOC_PHP_PLUGIN_PATH) \
			$(file)))

protos-cleanup:
	rm -r $(PROTO_DEST_DIR)